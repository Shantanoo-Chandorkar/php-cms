<?php

namespace Widget_Corp_Oops_Helper;

use Widget_Corp_Oops_Admin\Models\User;
use PDO;
use PDOException;

require_once 'constants.php';

class DBConnection
{
    private $database = '';
    public $conn      = null;

    public function __construct($dbname)
    {
        $this->database = $dbname;
        $this->connect($this->database);
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

    public function register_user($username, $password)
    {

        if (empty($username) || empty($password)) {
            return array(
                'success' => false,
                'message' => 'Username or password cannot be empty.',
            );
        }

        // check if username exists
        $query = $this->conn->prepare('SELECT id FROM users WHERE username = :username');
        $query->execute(array( ':username' => $username ));
        if ($query->fetch()) {
            return array(
                'success' => false,
                'message' => 'Username already taken',
            );
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $insert = $this->conn->prepare(
            'INSERT INTO users (username, hashed_password) VALUES (:username, :hashed_password)'
        );
        $insert->execute(
            array(
                ':username'        => $username,
                ':hashed_password' => $hashed,
            )
        );

        return array(
            'success' => true,
            'message' => 'Registration successful!',
        );
    }

    public function login_user($username, $password)
    {
        $query = $this->conn->prepare(
            'SELECT id, hashed_password FROM users WHERE username = :username LIMIT 1'
        );
        $query->execute(array( ':username' => $username ));
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['hashed_password'])) {
            // Start session and store user ID
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $username;
            return array(
                'success' => true,
                'message' => 'Login successful!',
            );
        }

        return array(
            'success' => false,
            'message' => 'Invalid username or password.',
        );
    }

    // public function get_all_users() {
    // try {
    // $query = $this->conn->query("SELECT id, username, role FROM users ORDER BY username ASC");
    // return $query->fetchAll(PDO::FETCH_ASSOC);
    // } catch (PDOException $e) {
    // die("Error fetching users: " . $e->getMessage());
    // }
    // }

    public function get_all_users(): array
    {
        try {
            $query = $this->conn->query('SELECT id, username, role FROM users ORDER BY username ASC');
            $rows  = $query->fetchAll(PDO::FETCH_ASSOC);

            $users = array();
            foreach ($rows as $row) {
                $users[] = new User(
                    (int) $row['id'],
                    $row['username'],
                    $row['role']
                );
            }
            return $users;
        } catch (PDOException $e) {
            die('Error fetching users: ' . $e->getMessage());
        }
    }


    public function create_new_user($username, $hashed_password, $role)
    {
        $query = $this->conn->prepare('SELECT id FROM users WHERE username = :username');
        $query->execute(array( ':username' => $username ));
        if ($query->fetch()) {
            return false;
        }

        $query = $this->conn->prepare('INSERT INTO users (username, hashed_password, role) VALUES (:username, :hashed_password, :role)');
        $query->execute(
            array(
                ':username'        => $username,
                ':hashed_password' => $hashed_password,
                ':role'            => $role,
            )
        );
        return $this->conn->lastInsertId();
    }

    public function get_user_by_id($id)
    {
        $query = $this->conn->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $query->execute(array( ':id' => $id ));
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function update_user($id, $username, $hashed_password, $role)
    {
        $query = $this->conn->prepare('UPDATE users SET username = :username, hashed_password = :hashed_password, role = :role WHERE id = :id LIMIT 1');
        return $query->execute(
            array(
                ':username'        => $username,
                ':hashed_password' => $hashed_password,
                ':role'            => $role,
                ':id'              => $id,
            )
        );
    }

    public function delete_user_by_id($user_id)
    {
        try {
            if ($this->get_user_by_id($user_id) !== null) {
                $query = $this->conn->prepare(
                    'DELETE FROM users WHERE id = :id LIMIT 1'
                );

                $query->bindParam(':id', $user_id, PDO::PARAM_INT);

                $query->execute();
                return $query->rowCount();
            }
            return 0;
        } catch (PDOException $e) {
            die('Error while deleting the subject: ' . $e->getMessage());
        }
    }


    public function close()
    {
        if (isset($this->conn)) {
            $this->conn = null;
        }
    }
}
