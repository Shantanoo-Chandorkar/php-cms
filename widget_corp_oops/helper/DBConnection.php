<?php

namespace Widget_Corp_Oops_Helper;

use PDO;
use PDOException;

require_once __DIR__ . '/constants.php';

class DBConnection
{
    private ?PDO $conn = null;

    public function __construct(?PDO $externalConn = null)
    {
        if ($externalConn !== null) {
            $this->conn = $externalConn;
        }
    }

    public function getConnection(): PDO
    {
        if ($this->conn === null) {
            $this->conn = $this->connect();
        }
        return $this->conn;
    }

    private function connect(): PDO
    {
        try {
            $pdo = new PDO(
                'mysql:host=' . SERVER_NAME . ';dbname=' . DATABASE_NAME . ';charset=utf8',
                USER_NAME,
                PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            return $pdo;
        } catch (PDOException $e) {
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
        }
    }
}
