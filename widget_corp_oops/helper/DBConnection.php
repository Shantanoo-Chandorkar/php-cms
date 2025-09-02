<?php

namespace Widget_Corp_Oops_Helper;

use Widget_Corp_Oops_Admin\Models\User;
use PDO;
use PDOException;

require_once 'constants.php';

class DBConnection
{
    private string $database = '';
    public ?PDO $conn      = null;

    public function __construct(string $dbname, ?PDO $externalConn = null)
    {
        $this->database = $dbname;

        // If an external PDO connection is provided, use it.
        if ($externalConn !== null) {
            // Use injected PDO connection
            $this->conn = $externalConn;
        } else {
            // Default: create a fresh connection
            $this->connect($this->database);
        }
    }

    private function connect($dbname)
    {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    'mysql:host=' . SERVER_NAME . ";dbname={$dbname};charset=utf8",
                    USER_NAME,
                    PASSWORD
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }
        return $this->conn;
    }

    public function get_subjects()
    {
        try {
            $query = $this->conn->query('SELECT * FROM subjects ORDER BY position ASC');
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error fetchig subjects: ' . $e->getMessage());
        }
    }

    public function get_pages($subject_id)
    {
        try {
            // :subject_id is a bound parameter so PDO ensures it's treated as a value,
            // and not an executable SQL.
            $query = $this->conn->prepare(
                'SELECT * FROM pages WHERE subject_id = :subject_id ORDER BY position ASC'
            );
            $query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error fetching pages: ' . $e->getMessage());
        }
    }

    public function get_subject_by_id($id)
    {
        try {
            // :id is a bound parameter so PDO ensures it's treated as a value,
            // and not an executable SQL.
            $query = $this->conn->prepare(
                'SELECT * FROM subjects WHERE id = :id LIMIT 1'
            );
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            die('Error fetching subject requested: ' . $e->getMessage());
        }
    }

    public function get_page_by_id($id)
    {
        try {
            // :id is a bound parameter so PDO ensures it's treated as a value,
            // and not an executable SQL.
            $query = $this->conn->prepare(
                'SELECT * FROM pages WHERE id = :id LIMIT 1'
            );
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            die('Error fetching page requested: ' . $e->getMessage());
        }
    }

    public function count_pages_for_subject($subject_id)
    {
        try {
            $stmt = $this->conn->prepare(
                'SELECT COUNT(*) FROM pages WHERE subject_id = :subject_id'
            );
            $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $stmt->execute();

            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            die('Error counting pages: ' . $e->getMessage());
        }
    }

    public function get_first_position_page_by_subject_id_with($subject_id)
    {
        try {
            $query = $this->conn->prepare(
                'SELECT * FROM pages WHERE subject_id = :subject_id ORDER BY position ASC LIMIT 1'
            );
            $query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error fetching page: ' . $e->getMessage());
        }
    }

    public function close()
    {
        if (isset($this->conn)) {
            $this->conn = null;
        }
    }
}
