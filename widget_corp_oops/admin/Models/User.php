<?php

namespace Widget_Corp_Oops_Admin\Models;

use PDO;
use PDOException;
use Widget_Corp_Oops_Helper\DBConnection;

class User
{
    public ?int $id;
    public ?string $username;
    public ?string $password;
    public ?string $role;
    private DBConnection $db;

    public function __construct(
        ?DBConnection $db = null,
        ?int $id = null,
        ?string $username = null,
        ?string $password = null,
        ?string $role = null,
    ) {
        $this->id       = $id;
        $this->username = $username;
        $this->password = $password;
        $this->role     = $role;
        $this->db       = $db ?? new DBConnection();
    }

    // Getters.
    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    // Setters.
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    // Authentication methods
    public function registerUser(string $username, string $password): array
    {
        try {
            if (empty($username) || empty($password)) {
                return array(
                    'success' => false,
                    'message' => 'Username or password cannot be empty.',
                );
            }

            $existing_user = $this->getUserByUserName($username);
            if ($existing_user) {
                return array(
                    'success' => false,
                    'message' => 'Username already taken',
                );
            }

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = $this->db->getConnection()->prepare(
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
        } catch (PDOException $e) {
            error_log('Error while registering: ' . $e->getMessage());
            return [];
        }
    }

    public function loginUser(string $username, string $password)
    {
        try {
            $query = $this->db->getConnection()->prepare(
                'SELECT id, hashed_password, role FROM users WHERE username = :username LIMIT 1'
            );
            $query->execute(array( ':username' => $username ));
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user && $user['role'] === 'subscriber') {
                return array(
                    'success' => false,
                    'message' => 'Access Denied.',
                );
            }

            if ($user && password_verify($password, $user['hashed_password']) && $user['role'] === 'admin') {
                return array(
                    'success' => true,
                    'message' => 'Login successful!',
                );
            }

            return array(
                'success' => false,
                'message' => 'Invalid username or password.',
            );
        } catch (PDOException $e) {
            error_log('Error while login: ' . $e->getMessage());
            return [];
        }
    }

    public function getAllUsers(): array
    {
        try {
            $query = $this->db->getConnection()->query('SELECT id, username, role FROM users ORDER BY username ASC');
            $rows  = $query->fetchAll(PDO::FETCH_ASSOC);

            $users = array();
            foreach ($rows as $row) {
                $users[] = new User(
                    null,
                    (int) $row['id'],
                    $row['username'],
                    null,
                    $row['role']
                );
            }
            return $users;
        } catch (PDOException $e) {
            die('Error while fetching users: ' . $e->getMessage());
        }
    }

    public function createNewUser(string $username, string $hashed_password, string $role): array
    {
        try {
            $existing_user = $this->getUserByUserName($username);
            if ($existing_user) {
                return array(
                    'success' => false,
                    'message' => 'Username already taken',
                );
            }

            $query = $this->db->getConnection()->prepare(
                'INSERT INTO users (username, hashed_password, role) 
                VALUES (:username, :hashed_password, :role)'
            );
            $query->execute(
                array(
                    ':username'        => $username,
                    ':hashed_password' => $hashed_password,
                    ':role'            => $role,
                )
            );
            return array(
                'success' => true,
                'message' => $this->db->getConnection()->lastInsertId()
            );
        } catch (PDOException $e) {
            die('Error while creating user: ' . $e->getMessage());
        }
    }

    public function getUserByUserName(string $username): ?array
    {
        try {
            $query = $this->db->getConnection()->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
            $query->execute(array( ':username' => $username ));
            $user = $query->fetch(PDO::FETCH_ASSOC);

            return $user ?: null;
        } catch (PDOException $e) {
            die('Error fetching user requested: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function getUserById(int $id): ?array
    {
        try {
            $query = $this->db->getConnection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
            $query->execute(array( ':id' => $id ));
            $user = $query->fetch(PDO::FETCH_ASSOC);

            return $user ?: null;
        } catch (PDOException $e) {
            die('Error fetching user requested: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function updateUser(
        int $id,
        ?string $username = null,
        ?string $hashed_password = null,
        ?string $role = null
    ): bool {
        try {
            $existing_user = $this->getUserById($id);
            if ($existing_user === null) {
                return false;
            }

            // Build the dynamic query
            $fields = [];
            $params = [':id' => $id];

            if ($username !== null) {
                $fields[] = 'username = :username';
                $params[':username'] = $username;
            }
            if ($hashed_password !== null) {
                $fields[] = 'hashed_password = :hashed_password';
                $params[':hashed_password'] = $hashed_password;
            }
            if ($role !== null) {
                $fields[] = 'role = :role';
                $params[':role'] = $role;
            }

            // If no fields provided, nothing to update
            if (empty($fields)) {
                return false;
            }

            $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id LIMIT 1';

            $query = $this->db->getConnection()->prepare($sql);
            $query->execute($params);

            // Return true if any row was updated
            return $query->rowCount() > 0;
        } catch (PDOException $e) {
            die('Error updating user requested: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function deleteUserById(string $user_id)
    {
        try {
            if ($this->getUserById($user_id) !== null) {
                $query = $this->db->getConnection()->prepare(
                    'DELETE FROM users WHERE id = :id LIMIT 1'
                );

                $query->bindParam(':id', $user_id, PDO::PARAM_INT);

                $query->execute();

                // Return true if any row was deleted
                return $query->rowCount() > 0;
            }
            return false;
        } catch (PDOException $e) {
            die('Error while deleting the subject: ' . $e->getMessage());
        }
    }
}
