<?php

namespace Bpjs\Core;
use Helpers\Api;
use Helpers\Route;

class Kernel
{
    protected array $middleware = [
        \Helpers\CORSMiddleware::class,
    ]; 
    protected string $dispatcherType = 'web';

    public function __construct(protected App $app)
    {
        $this->mapRoutes();
    }

    protected function mapRoutes(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
        $appBasePath = app_base_path();
        $cleanUri = preg_replace('#^' . preg_quote($appBasePath, '#') . '#', '', $uri);
        $cleanUri = '/' . ltrim($cleanUri, '/');

        $apiPrefix = '/api';

        if (str_starts_with($cleanUri, $apiPrefix)) {
            $this->dispatcherType = 'api';
            Api::init(api_prefix()); // contoh: /bpjs-framework/api
            require BPJS_BASE_PATH . '/routes/api.php';
        } else {
            $this->dispatcherType = 'web';
            Route::init($appBasePath); // contoh: /bpjs-framework
            require BPJS_BASE_PATH . '/routes/web.php';
        }
    }

    public function handle(Request $request): Response
    {
        foreach ($this->middleware as $middleware) {
            (new $middleware())->handle($request);
        }

        return match ($this->dispatcherType) {
            'web' => Route::dispatch(),
            'api' => Api::dispatch(),
            default => new \Bpjs\Core\Response('Dispatcher not found', 500)
        };
    }

    public function terminate(): void
    {
        // Bisa untuk logging, session cleanup, dsb.
    }

    public function addMiddleware(string $class): void
    {
        $this->middleware[] = $class;
    }
}
