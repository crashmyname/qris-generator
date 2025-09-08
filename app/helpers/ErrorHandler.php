<?php
namespace Helpers;

use Throwable;

class ErrorHandler
{
    protected static $additionalData = [];

    // Daftarkan error handler untuk menangani exception & error
    public static function register()
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    // Menangani exception
    public static function handleException(Throwable $exception)
    {
        self::logError(
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
        self::renderErrorPage($exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
    }

    // Menangani error PHP
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        self::logError($errstr, $errfile, $errline);
        self::renderErrorPage($errstr, $errfile, $errline);
    }

    // Menangani error shutdown
    public static function handleShutdown()
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::logError($error['message'], $error['file'], $error['line']);
            self::renderErrorPage($error['message'], $error['file'], $error['line']);
        }
    }

    // Fungsi untuk log error ke file/log system
    public static function logError($message, $file, $line, $trace = null)
    {
        $logDir = BPJS_BASE_PATH . '/logs';
        $logFile = $logDir . '/error.log';

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        // Simpan log ke file atau monitoring system
        $log = "[Error] $message in $file at line $line";
        if ($trace) {
            $log .= "\nTrace: $trace";
        }
        file_put_contents($logFile, $log . "\n", FILE_APPEND);
    }

    // Menampilkan halaman error dengan informasi tambahan
    public static function renderErrorPage($message, $file, $line, $trace = null)
    {
        if(env('APP_DEBUG') == false){
            $data = [
                'message' => $message,
                'file' => $file,
                'line' => $line,
                'trace' => $trace,
                'additionalData' => self::$additionalData,
            ];
            include BPJS_BASE_PATH . '/app/handle/errors/page_error.php';
            exit();
        } else {
            View::error('500');
        }
    }

    // Tambahkan data tambahan (dinamis)
    public static function addAdditionalData($key, $value)
    {
        self::$additionalData[$key] = $value;
    }
}
