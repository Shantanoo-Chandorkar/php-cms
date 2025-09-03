<?php

namespace Widget_Corp_Oops_Admin\Models;

use PDO;
use PDOException;
use Widget_Corp_Oops_Helper\DBConnection;

class Subject
{
    private ?int $id;
    private ?string $menu_name;
    private ?int $position;
    private ?bool $visible;
    private DBConnection $db;

    public function __construct(
        ?DBConnection $db = null,
        ?int $id = null,
        ?string $menu_name = null,
        ?int $position = null,
        ?bool $visible = null,
    ) {
        $this->id        = $id;
        $this->menu_name = $menu_name;
        $this->position  = $position;
        $this->visible   = $visible;
        $this->db        = $db ?? new DBConnection();
    }

    // Getters.
    public function getId(): int
    {
        return $this->id;
    }

    public function getMenuName(): string
    {
        return $this->menu_name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    // Setters
    public function setMenuName(string $menu_name): void
    {
        $this->menu_name = $menu_name;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    // Database operations
    public function getSubjects(): array
    {
        try {
            $query = $this->db->getConnection()->query('SELECT * FROM subjects ORDER BY position ASC');
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error fetchig subjects: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function getSubjectById($id): ?array
    {
        try {
            // :id is a bound parameter so PDO ensures it's treated as a value,
            // and not an executable SQL.
            $query = $this->db->getConnection()->prepare(
                'SELECT * FROM subjects WHERE id = :id LIMIT 1'
            );
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            die('Error fetching subject requested: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function getSubjectByMenuName($menu_name): ?array
    {
        try {
            // :menu_name is a bound parameter so PDO ensures it's treated as a value,
            // and not an executable SQL.
            $query = $this->db->getConnection()->prepare(
                'SELECT * FROM subjects WHERE menu_name = :menu_name LIMIT 1'
            );
            $query->bindParam(':menu_name', $menu_name, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            die('Error fetching subject requested: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function createNewSubject($menu_name, $position, $visible): ?int
    {
        try {
            // enforce uniqueness
            if ($this->getSubjectByMenuName($menu_name)) {
                throw new \RuntimeException("Subject with menu_name '$menu_name' already exists.");
            }

            $query = $this->db->getConnection()->prepare(
                'INSERT INTO subjects (menu_name, position, visible)
                VALUES (:menu_name, :position, :visible)'
            );

            $query->bindParam(':menu_name', $menu_name, PDO::PARAM_STR);
            $query->bindParam(':position', $position, PDO::PARAM_INT);
            $query->bindParam(':visible', $visible, PDO::PARAM_INT);

            $query->execute();

            // Return the new subject ID
            return (int) $this->db->getConnection()->lastInsertId();
        } catch (PDOException $e) {
            die('Error creating new subject: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function updateSubject($id, $menu_name, $position, $visible): int
    {
        try {
            $query = $this->db->getConnection()->prepare(
                'UPDATE subjects 
                SET menu_name = :menu_name, position = :position, visible = :visible 
                WHERE id = :id'
            );

            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':menu_name', $menu_name, PDO::PARAM_STR);
            $query->bindParam(':position', $position, PDO::PARAM_INT);
            $query->bindParam(':visible', $visible, PDO::PARAM_INT);

            $query->execute();

            // Return number of affected rows.
            return $query->rowCount();
        } catch (PDOException $e) {
            die('Error creating new subject: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function deleteSubjectById($subject_id): int
    {
        try {
            if (null !== $this->getSubjectById($subject_id)) {
                $query = $this->db->getConnection()->prepare(
                    'DELETE FROM subjects WHERE id = :id LIMIT 1'
                );

                $query->bindParam(':id', $subject_id, PDO::PARAM_INT);

                $query->execute();
                return $query->rowCount();
            }
            return 0;
        } catch (PDOException $e) {
            die('Error while deleting the subject: ' . htmlspecialchars($e->getMessage()));
        }
    }
}
