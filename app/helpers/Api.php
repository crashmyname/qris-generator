<?php
namespace Helpers;

use Helpers\View;

class Api
{
    private static $routes = [];
    private static $names = [];
    private static $prefix;
    private static $groupMiddlewares = []; // Menyimpan middleware grup sementara
    private static $lastRouteMethod = null;
    private static $lastRouteUri = null;
    // Inisialisasi API dengan prefix
    public static function init($prefix = '')
    {
        self::$routes['GET'] = [];
        self::$routes['POST'] = [];
        self::$routes['PUT'] = [];
        self::$routes['DELETE'] = [];
        self::$prefix = rtrim($prefix, '/');
    }

    // Menambahkan rute GET dengan middleware
    public static function get($uri, $handler, $middlewares = [])
    {
        $middlewares = array_merge(self::$groupMiddlewares, $middlewares);
        self::$routes['GET'][$uri] = [
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
        return new self(); // Untuk chaining
    }

    // Menambahkan rute POST dengan middleware
    public static function post($uri, $handler, $middlewares = [])
    {
        $middlewares = array_merge(self::$groupMiddlewares, $middlewares);
        self::$routes['POST'][$uri] = [
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
        return new self();
    }
    public static function put($uri, $handler, $middlewares = [])
    {
        $middlewares = array_merge(self::$groupMiddlewares, $middlewares);
        self::$routes['PUT'][$uri] = [
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
        return new self();
    }

    // Menambahkan rute DELETE dengan middleware
    public static function delete($uri, $handler, $middlewares = [])
    {
        $middlewares = array_merge(self::$groupMiddlewares, $middlewares);
        self::$routes['DELETE'][$uri] = [
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
        return new self();
    }

    // Menambahkan grup middleware ke beberapa rute
    public static function group(array $middlewares, \Closure $routes)
    {
        self::$groupMiddlewares = $middlewares;

        call_user_func($routes);

        self::$groupMiddlewares = [];
    }
    public static function name($name)
    {
        // Memeriksa rute untuk GET, POST, PUT, atau DELETE
        foreach (['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            if (!empty(self::$routes[$method])) {
                $lastRoute = array_key_last(self::$routes[$method]);
                self::$names[$name] = $lastRoute;

                // Debug log untuk memeriksa nama dan URI yang dipetakan
                error_log("Route name '{$name}' mapped to URI '{$lastRoute}'");

                return new self(); // Kembali ke chaining
            }
        }

        throw new \Exception("No routes found for naming '{$name}'");
    }
    public static function route($name, $params = [])
    {
        if (isset(self::$names[$name])) {
            $uri = self::$names[$name];

            // Mengganti parameter {param} di URL dengan nilai dari $params
            foreach ($params as $key => $value) {
                $uri = str_replace('{' . $key . '}', $value, $uri);
            }

            // Menentukan apakah prefix harus ditambahkan
            if (php_sapi_name() === 'cli-server' || PHP_SAPI === 'cli') {
                return '/' . trim($uri, '/'); // Tidak menggunakan prefix saat dijalankan dari PHP CLI
            }

            return self::$prefix . '/' . trim($uri, '/');
        }

        self::renderErrorPage("Route dengan nama '{$name}' tidak ditemukan.");
    }

    // Dispatch routing
    public static function dispatch(): \Bpjs\Core\Response
    {
        try {

            $method = $_SERVER['REQUEST_METHOD'];
            if ($method === 'POST' && isset($_POST['_method'])) {
                $method = strtoupper($_POST['_method']);
            }

            $uri = strtok($_SERVER['REQUEST_URI'], '?');

            // Potong prefix jika cocok
            if (self::$prefix && str_starts_with($uri, self::$prefix)) {
                $uri = substr($uri, strlen(self::$prefix));
            }

            $uri = '/' . ltrim($uri, '/'); // Pastikan selalu format /xxx
            if ($uri === '') $uri = '/';  // Fallback root

            $route = self::findRoute($method, $uri);

            if ($route) {
                $handler = $route['handler'];
                $middlewares = $route['middlewares'];
                $params = $route['params'] ?? [];

                $request = new \Bpjs\Core\Request();

                // Middleware
                foreach ($middlewares as $middleware) {
                    if (is_string($middleware)) {
                        $middlewareInstance = new $middleware();
                        if (method_exists($middlewareInstance, 'handle')) {
                            $middlewareInstance->handle($request);
                        }
                    } elseif (is_callable($middleware)) {
                        $middleware($request);
                    }
                }

                // CSRF protection jika perlu
                if ($method === 'POST') {
                    $csrfToken = $request->get('csrf_token') ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
                    if (empty($csrfToken) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
                        return new \Bpjs\Core\Response('Invalid CSRF Token', 419);
                    }
                }

                // Jalankan controller atau closure
                if (is_array($handler) && count($handler) === 2) {
                    [$controller, $method] = $handler;
                    $controllerInstance = new $controller();
                    $reflection = new \ReflectionMethod($controllerInstance, $method);
                    $parameters = $reflection->getParameters();
                    if (isset($parameters[0]) && $parameters[0]->getType()?->getName() === \Bpjs\Core\Request::class) {
                        array_unshift($params, $request);
                    }
                    $result = call_user_func_array([$controllerInstance, $method], $params);
                } else {
                    $result = call_user_func_array($handler, $params);
                }

                return $result instanceof \Bpjs\Core\Response
                    ? $result
                    : new \Bpjs\Core\Response($result);
            }

            // Route tidak ditemukan
            ob_start();
            include BPJS_BASE_PATH . '/app/handle/errors/404.php';
            $content = ob_get_clean();
            return new \Bpjs\Core\Response($content, 404);

        } catch (\Throwable $e) {
            ob_start();
            include BPJS_BASE_PATH . '/app/handle/errors/500.php';
            $content = ob_get_clean();
            return new \Bpjs\Core\Response($content, 500);
        }
    }

    // Mencari rute berdasarkan metode dan URI
    private static function findRoute($method, $uri)
    {
        foreach (self::$routes[$method] as $routeUri => $route) {
            // Mencocokkan URI dengan parameter
            $routePattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_\-]+)', $routeUri);
            if (preg_match('#^' . $routePattern . '$#', $uri, $matches)) {
                // Ambil parameter dari URI
                array_shift($matches); // Hapus elemen pertama yang merupakan keseluruhan URI yang dicocokkan
                $route['params'] = $matches; // Tambahkan parameter ke route
                return $route;
            }
        }
        return null; // Tidak ada rute yang ditemukan
    }

    // Cek apakah route ada
    private static function routeExists($uri)
    {
        return isset(self::$routes['GET'][$uri]) || isset(self::$routes['POST'][$uri]);
    }
    private static function renderErrorPage($message)
    {
        // Pastikan tidak ada output lain yang dikirim sebelum HTML error ditampilkan
        ob_clean(); // Membersihkan output buffer, jika ada yang terkirim sebelumnya
        header('Content-Type: text/html; charset=utf-8');
        $url = base_url();
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f9;
                    color: #333;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .error-container {
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 20px;
                    max-width: 600px;
                    width: 100%;
                    text-align: center;
                }
                h1 {
                    color: #e74c3c;
                    font-size: 2em;
                }
                p {
                    font-size: 1.2em;
                    margin: 15px 0;
                }
                a {
                    color: #3498db;
                    text-decoration: none;
                }
                a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <h1>Error: {$message}</h1>
                <p>Something went wrong while processing the request.</p>
                <p><a href='{$url}'>Return to Home</a></p>
            </div>
        </body>
        </html>
    ";
        exit(); // Menghentikan eksekusi skrip setelah error page ditampilkan
    }
}
