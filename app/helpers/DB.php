<?php

namespace Helpers;

use PDO;
use PDOException;
use Helpers\Database;

class DB
{
    private static $conn = null;
    protected $table;
    protected $query = '';
    protected $params = [];
    protected $joins = [];
    protected $conditions = [];
    protected $limit;
    protected $offset;
    protected $selectColumns = ['*'];  // Default select columns

    // Mendapatkan koneksi database
    public static function getConnection()
    {
        if (self::$conn === null) {
            self::$conn = Database::connection();
        }
        return self::$conn;
    }

    // Mulai transaksi
    public static function beginTransaction()
    {
        return self::getConnection()->beginTransaction();
    }

    // Commit transaksi
    public static function commit()
    {
        return self::getConnection()->commit();
    }

    // Rollback transaksi
    public static function rollback()
    {
        return self::getConnection()->rollback();
    }

    // Menetapkan nama tabel
    public static function table($table)
    {
        $instance = new self();
        $instance->table = $table;
        return $instance;
    }

    // Union query
    public function union($query)
    {
        $this->query .= " UNION ($query)";
        return $this;
    }

    // Union All query
    public function unionAll($query)
    {
        $this->query .= " UNION ALL ($query)";
        return $this;
    }

    // Lock untuk update
    public function lockForUpdate()
    {
        $this->query .= ' FOR UPDATE';
        return $this;
    }

    // Lock bersama
    public function sharedLock()
    {
        $this->query .= ' LOCK IN SHARE MODE';
        return $this;
    }

    // Menetapkan kolom yang ingin diambil
    public function select(...$columns)
    {
        // Jika tidak ada kolom yang ditentukan, gunakan '*' sebagai default
        $this->selectColumns = empty($columns) ? ['*'] : $columns;
        $this->query = 'SELECT ' . implode(', ', $this->selectColumns) . ' FROM ' . $this->table;
        return $this;
    }

    // Menambahkan JOIN
    public function join($table, $first, $operator, $second, $type = 'INNER')
    {
        $this->joins[] = "$type JOIN $table ON $first $operator $second";
        return $this;
    }

    // Menambahkan kondisi WHERE
    public function where($column, $operator, $value)
    {
        $placeholder = ':' . str_replace('.', '_', $column) . count($this->params);
        $this->conditions[] = "$column $operator $placeholder";
        $this->params[$placeholder] = $value;
        return $this;
    }

    // Menambahkan kondisi OR WHERE
    public function orWhere($column, $operator, $value)
    {
        $placeholder = ':' . str_replace('.', '_', $column) . count($this->params);
        $this->conditions[] = "OR $column $operator $placeholder";
        $this->params[$placeholder] = $value;
        return $this;
    }

    // Membatasi jumlah hasil query
    public function limit($limit, $offset = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    // Mendapatkan hasil query
    public function get($fetchStyle = PDO::FETCH_OBJ)
    {
        $sql = $this->query;
        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }
        if ($this->conditions) {
            $sql .= ' WHERE ' . implode(' ', $this->conditions);
        }
        if ($this->limit) {
            $sql .= ' LIMIT ' . $this->limit;
            if ($this->offset) {
                $sql .= ' OFFSET ' . $this->offset;
            }
        }

        return $this->executeQuery($sql, $fetchStyle);
    }

    // Mendapatkan satu baris hasil query
    public function first($fetchStyle = PDO::FETCH_OBJ)
    {
        $stmt = self::getConnection()->prepare($this->query . ' LIMIT 1');
        $stmt->execute($this->params);
        return $stmt->fetch($fetchStyle);
    }

    // Menyisipkan data
    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $this->query = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
        
        return $this->executeQuery($this->query, PDO::FETCH_OBJ, $data);
    }

    // Memperbarui data
    public function update($data)
    {
        $setClause = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));
        $this->query = "UPDATE $this->table SET $setClause" . ($this->query ? " WHERE 1=1 " . $this->query : '');
        
        return $this->executeQuery($this->query, PDO::FETCH_OBJ, $data);
    }

    // Menghapus data
    public function delete()
    {
        $this->query = "DELETE FROM $this->table " . $this->query;
        return $this->executeQuery($this->query);
    }

    // Query mentah
    public static function raw($query, $params = [], $fetchStyle = PDO::FETCH_OBJ)
    {
        try {
            $stmt = self::getConnection()->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll($fetchStyle);
        } catch (PDOException $e) {
            // Tangani error dengan lebih baik
            error_log("Database Query Error: " . $e->getMessage());
            return []; // Mengembalikan array kosong jika error
        }
    }

    // Eksekusi query umum
    protected function executeQuery($query, $fetchStyle = PDO::FETCH_OBJ, $params = [])
    {
        try {
            $stmt = self::getConnection()->prepare($query);
            $params = empty($params) ? $this->params : $params;
            $stmt->execute($params);
            return $stmt->fetchAll($fetchStyle);
        } catch (PDOException $e) {
            // Tangani error dan log jika perlu
            error_log("Database Query Error: " . $e->getMessage());
            return []; // Mengembalikan array kosong jika terjadi error
        }
    }

    // Menampilkan tabel
    public static function showTables()
    {
        $dbType = self::getConnection()->getAttribute(PDO::ATTR_DRIVER_NAME);
        $sql = $dbType === 'mysql' ? "SHOW TABLES" : "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'";
        return self::fetchAll($sql);
    }

    // Membuat tabel
    public static function createTable($name, $columns)
    {
        $sql = "CREATE TABLE $name (" . implode(", ", $columns) . ")";
        return self::query($sql);
    }

    // Menghapus tabel
    public static function dropTable($name)
    {
        return self::query("DROP TABLE IF EXISTS $name");
    }

    // Query umum
    public static function query($sql, $params = [])
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Fetch semua data
    public static function fetchAll($sql, $params = [], $fetchStyle = PDO::FETCH_OBJ)
    {
        return self::query($sql, $params)->fetchAll($fetchStyle);
    }

    // Fetch satu data
    public static function fetch($sql, $params = [], $fetchStyle = PDO::FETCH_OBJ)
    {
        return self::query($sql, $params)->fetch($fetchStyle);
    }

    // Menghitung hasil query
    public static function count($sql, $params = [])
    {
        return self::query($sql, $params)->rowCount();
    }

    // Menangani error
    public static function renderError($exception)
    {
        static $errorDisplayed = false;

        if (!$errorDisplayed) {
            $errorDisplayed = true;
            if (!headers_sent()) {
                http_response_code(500);
            }
            $exceptionData = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
            extract($exceptionData);
            include __DIR__ . '/../../app/handle/errors/page_error.php';
        }
        exit();
    }
}
