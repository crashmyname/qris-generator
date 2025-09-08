<?php
use Helpers\Route;
use Helpers\BaseController;
function asset($path)
{
    $baseURL = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $baseURL .= $_SERVER['HTTP_HOST'] . $baseDir;

    return $baseURL . 'public/' . $path;
}

function module($path)
{
    $baseURL = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $baseURL .= $_SERVER['HTTP_HOST'] . $baseDir;

    return $baseURL . 'node_modules/' . $path;
}
function vendor($path)
{
    $baseURL = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $baseURL .= $_SERVER['HTTP_HOST'] . $baseDir;

    return $baseURL . 'vendor/' . $path;
}

function base_url()
{
    $basecontroller = new BaseController();
    return $basecontroller->base_url();
}

function vd($data)
{
    $basecontroller = new BaseController();
    return $basecontroller->prettyPrint($data);
}
function route($name, $params = [])
{
    return Route::route($name, $params);
}

function redirect($url)
{
    $basecontroller = new BaseController();
    return $basecontroller->redirect($url);
}

function sanitize($data)
{
    $basecontroller = new BaseController();
    return $basecontroller->sanitize($data);
}

function json($data, $statusCode = 200)
{
    $basecontroller = new BaseController();
    return $basecontroller->jsonResponse($data, $statusCode);
}

function uploadFile($file, $destination)
{
    $basecontroller = new BaseController();
    return $basecontroller->uploadFile($file, $destination);
}

function strLimit($string, $limit = 100, $end = '...')
{
    $basecontroller = new BaseController();
    return $basecontroller->strLimit($string, $limit, $end);
}

function toSlug($string)
{
    $basecontroller = new BaseController();
    return $basecontroller->toSlug($string);
}

function arrayFlatten($array)
{
    $basecontroller = new BaseController();
    return $basecontroller->arrayFlatten($array);
}

function arrayGet($array, $key, $default = null)
{
    $basecontroller = new BaseController();
    return $basecontroller->arrayGet($array, $key, $default);
}

function generateRandomString($length = 10)
{
    $basecontroller = new BaseController();
    return $basecontroller->generateRandomString($length);
}

function toJson($data)
{
    $basecontroller = new BaseController();
    return $basecontroller->toJson($data);
}

function fromJson($json, $assoc = true)
{
    $basecontroller = new BaseController();
    return $basecontroller->fromJson($json, $assoc);
}

function paginate($totalItems, $perPage = 10, $page = 1, $url = '?')
{
    $basecontroller = new BaseController();
    return $basecontroller->paginate($totalItems, $perPage, $page, $url);
}

function pathJoin(...$paths)
{
    $basecontroller = new BaseController();
    return $basecontroller->pathJoin($paths);
}

function rateLimit($key, $maxAttempts = 5, $seconds = 60)
{
    $basecontroller = new BaseController();
    return $basecontroller->rateLimit($key, $maxAttempts, $seconds);
}

function generateSlug($text)
{
    $basecontroller = new BaseController();
    return $basecontroller->generateSlug($text);
}

function sortByKey($array, $key)
{
    $basecontroller = new BaseController();
    return $basecontroller->sortByKey($array, $key);
}

function htmlEscape($string)
{
    $basecontroller = new BaseController();
    return $basecontroller->htmlEscape($string);
}

function buildUrl($base, $params = [])
{
    $basecontroller = new BaseController();
    return $basecontroller->buildUrl($base, $params);
}

function currentUrl()
{
    $basecontroller = new BaseController();
    return $basecontroller->currentUrl();
}

function formatNumber($number, $decimals = 2)
{
    $basecontroller = new BaseController();
    return $basecontroller->formatNumber($number, $decimals);
}

function isValidEmail($email)
{
    $basecontroller = new BaseController();
    return $basecontroller->isValidEmail($email);
}

function isValidUrl($url)
{
    $basecontroller = new BaseController();
    return $basecontroller->isValidUrl($url);
}

function setFlashMessage($key, $message)
{
    $basecontroller = new BaseController();
    return $basecontroller->setFlashMessage($key, $message);
}

function getFlashMessage($key)
{
    $basecontroller = new BaseController();
    return $basecontroller->getFlashMessage($key);
}

function encrypt($data, $key)
{
    $basecontroller = new BaseController();
    return $basecontroller->encrypt($data, $key);
}

function decrypt($data, $key)
{
    $basecontroller = new BaseController();
    return $basecontroller->decrypt($data, $key);
}

function arrayPluck($array, $key)
{
    $basecontroller = new BaseController();
    return $basecontroller->arrayPluck($array, $key);
}

function formatCurrency($amount, $currency = 'USD')
{
    $basecontroller = new BaseController();
    return $basecontroller->formatCurrency($amount, $currency);
}

function hashPassword($password)
{
    $basecontroller = new BaseController();
    return $basecontroller->hashPassword($password);
}

function verifyPassword($password, $hash)
{
    $basecontroller = new BaseController();
    return $basecontroller->verifyPassword($password, $hash);
}

function logMessage($message, $level = 'INFO')
{
    $basecontroller = new BaseController();
    return $basecontroller->logMessage($message, $level);
}

function arrayFilterByKey($array, $key, $value)
{
    $basecontroller = new BaseController();
    return $basecontroller->arrayFilterByKey($array, $key, $value);
}

function method($method)
{
    $basecontroller = new BaseController();
    return $basecontroller->Method($method);
}

function toTitleCase($string)
{
    $basecontroller = new BaseController();
    return $basecontroller->toTitleCase($string);
}

function toSentenceCase($string)
{
    $basecontroller = new BaseController();
    return $basecontroller->toSentenceCase($string);
}

function toUpperCase($string)
{
    $basecontroller = new BaseController();
    return $basecontroller->toUpperCase($string);
}

function toLowerCase($string)
{
    $basecontroller = new BaseController();
    return $basecontroller->toLowerCase($string);
}

function csrf()
{
    $basecontroller = new BaseController();
    return $basecontroller->csrfToken();
}

function csrfToken()
{
    $basecontroller = new BaseController();
    return $basecontroller->csrfMeta();
}

function csrfHeader()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token)
{
    $basecontroller = new BaseController();
    return $basecontroller->verifyCsrfToken($token);
}

function setSecurityHeaders()
{
    $basecontroller = new BaseController();
    return $basecontroller->setSecurityHeaders();
}

function view($view, $data = [], $layout = null)
{
    $basecontroller = new BaseController();
    return $basecontroller->view($view, $data, $layout);
}

function back()
{
    $basecontroller = new BaseController();
    return $basecontroller->back();
}

function setTime()
{
    date_default_timezone_set('Asia/Jakarta');
}

function storeFile($file, $targetDirectory)
{
    // Cek apakah ada file yang diunggah
    if ($file['error'] === UPLOAD_ERR_OK) {
        $tmpName = $file['tmp_name'];
        $originalName = basename($file['name']);
        $targetPath = rtrim($targetDirectory, '/') . '/' . $originalName;

        // Pindahkan file dari lokasi sementara ke tujuan
        if (move_uploaded_file($tmpName, $targetPath)) {
            return ['status' => 'success', 'message' => 'File berhasil diunggah.', 'path' => $targetPath];
        } else {
            return ['status' => 'error', 'message' => 'Terjadi kesalahan saat memindahkan file.'];
        }
    } else {
        return ['status' => 'error', 'message' => 'File gagal diunggah.'];
    }
}

function store($file, $targetDirectory, $filename)
{
    if (is_array($file) && isset($file['tmp_name'])) {
        $file = $file['tmp_name'];
    }

    if (!is_dir($targetDirectory)) {
        mkdir($targetDirectory, 0777, true);
    }

    $targetFile = $targetDirectory . DIRECTORY_SEPARATOR . $filename;
    return move_uploaded_file($file, $targetFile);
}

if (!function_exists('public_path')) {
    function public_path($path = '')
    {
        return BPJS_BASE_PATH . '/public/' . $path;
    }
}

if (!function_exists('storage_path')) {
    function storage_path($path = '')
    {
        return rtrim(BPJS_BASE_PATH . '/storage/public/' . ltrim($path, '/'), DIRECTORY_SEPARATOR);
    }
}

function storage($path)
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? null;
    $physicalBasePath = BPJS_BASE_PATH;

    if ($documentRoot) {
        $documentRoot = realpath($documentRoot);
        $relativePath = str_replace('\\', '/', str_replace($documentRoot, '', $physicalBasePath));
        $relativePath = '/' . trim($relativePath, '/');
    } else {
        $relativePath = '/' . trim(basename($physicalBasePath), '/');
    }
    if ($relativePath === '/') {
        $relativePath = '';
    }

    return $protocol . $host . $relativePath . '/storage/public/'.ltrim($path,'/');
}

function storage_secure(string $filename, int $ttlSeconds = 3600): string
{
    $payload = json_encode([
        'f' => $filename,
        'exp' => time() + $ttlSeconds
    ]);

    $token = \Helpers\Crypto::encrypt($payload);

    return base_url() . 'file/secure?token=' . urlencode($token);
}

function serve_secure_file()
{
    $token = $_GET['token'] ?? null;

    if (!$token) {
        return \Helpers\Response::json(['status'=>400,'message'=>'Missing token'], 400);
    }

    $decoded = \Helpers\Crypto::decrypt($token);
    if (!$decoded) {
        return \Helpers\Response::json(['status'=>403,'message'=>'Invalid token'], 403);
    }

    $data = json_decode($decoded, true);
    if (!$data || !isset($data['f'], $data['exp'])) {
        return \Helpers\Response::json(['status'=>403,'message'=>'Invalid token data'], 403);
    }

    if (time() > $data['exp']) {
        return \Helpers\Response::json(['status'=>403,'message'=>'Token expired'], 403);
    }

    $filepath = storage_path($data['f']);
    if (!file_exists($filepath)) {
        return new \Bpjs\Core\Response('File not found', 404);
    }

    header('Content-Type: ' . mime_content_type($filepath));
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
}

function createToken()
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $token;
    return $token;
}

function env($key, $default = null)
{
    static $env = null;

    if ($env === null) {
        $envFilePath = BPJS_BASE_PATH . '/.env';
        if (file_exists($envFilePath)) {
            $env = parse_ini_file($envFilePath, false, INI_SCANNER_RAW);
            if ($env === false) {
                $env = [];
            }
        } else {
            $env = [];
        }

        // Set ke $_ENV untuk kompatibilitas jika diperlukan
        foreach ($env as $envKey => $envValue) {
            $_ENV[$envKey] = $envValue;
        }
    }

    // Ambil nilai berdasarkan key
    return array_key_exists($key, $env) ? $env[$key] : $default;
}

function config(string $key, $default = null)
{
    static $configs = [];

    // Cek apakah key valid, minimal harus ada satu titik
    if (!str_contains($key, '.')) {
        return $default;
    }

    [$file, $path] = explode('.', $key, 2);

    if (!isset($configs[$file])) {
        $pathToFile = BPJS_BASE_PATH . "/config/{$file}.php";
        if (!file_exists($pathToFile)) return $default;
        $configs[$file] = require $pathToFile;
    }

    $config = $configs[$file];

    foreach (explode('.', $path) as $segment) {
        if (!is_array($config) || !array_key_exists($segment, $config)) {
            return $default;
        }
        $config = $config[$segment];
    }

    return $config;
}

function api_prefix() {
    return app_base_path() . '/api';
}

function app_base_path(): string {
    return rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
}

function url($params){
    return base_url().$params;
}