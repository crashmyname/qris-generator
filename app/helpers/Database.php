<?php
namespace Helpers;

use PDO;
use Exception;

class Database
{
    protected static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $default = config('database.default', 'mysql');
        $connectionKey = "database.connections.$default";

        if (!config($connectionKey)) {
            throw new Exception("Database connection [$default] not defined.");
        }

        return self::$pdo = self::connect($connectionKey);
    }

    protected static function connect(string $baseKey): PDO
    {
        $driver   = config("$baseKey.driver");

        $host     = config("$baseKey.host", '127.0.0.1');
        $port     = config("$baseKey.port", '3306');
        $dbname   = config("$baseKey.database", 'bpjs');
        $charset  = config("$baseKey.charset", 'utf8mb4');
        $username = config("$baseKey.username", 'root');
        $password = config("$baseKey.password", '');
        $options  = config("$baseKey.options", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        switch ($driver) {
            case 'mysql':
                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
                break;

            case 'pgsql':
                $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
                break;

            case 'sqlite':
                $dsn = "sqlite:$dbname";
                break;

            case 'sqlsrv':
                $dsn = "sqlsrv:Server=$host,$port;Database=$dbname";
                break;

            default:
                throw new Exception("Driver [$driver] not supported.");
        }

        return new PDO($dsn, $username, $password, $options);
    }

    public static function disconnect(): void
    {
        self::$pdo = null;
    }
}
