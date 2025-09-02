<?php

namespace Widget_Corp_Oops_Admin\Models;

use PDO;
use PDOException;
use Widget_Corp_Oops_Helper\DBConnection;

class Page
{
    private ?int $id;
    private ?int $subject_id;
    private ?string $menu_name;
    private ?int $position;
    private ?bool $visible;
    private ?string $content;
    private DBConnection $db;

    public function __construct(
        ?int $id = null,
        ?int $subject_id = null,
        ?string $menu_name = null,
        ?int $position = null,
        ?bool $visible = null,
        ?string $content = null
    ) {
        $this->id         = $id;
        $this->subject_id = $subject_id;
        $this->menu_name  = $menu_name;
        $this->position   = $position;
        $this->visible    = $visible;
        $this->content    = $content;
        $this->db         = new DBConnection('widget_corp_test');
    }

    // Getters.
    public function getId(): int
    {
        return $this->id;
    }

    public function getSubjectId(): int
    {
        return $this->subject_id;
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

    public function getContent(): string
    {
        return $this->content;
    }

    // Setters
    public function setSubjectId(int $subject_id): void
    {
        $this->subject_id = $subject_id;
    }

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

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    // Database operations
    public function getPages(): array
    {
        try {
            $query = $this->db->conn->prepare(
                'SELECT * FROM pages ORDER BY subject_id, position ASC'
            );
            $query->execute();
            $pages = $query->fetchAll(PDO::FETCH_ASSOC);
            return $pages ?: [];
        } catch (PDOException $e) {
            error_log('Error while fetching all pages: ' . $e->getMessage());
            return [];
        }
    }

    public function getPagesBySubjectId(int $subject_id): array
    {
        try {
            // :subject_id is a bound parameter so PDO ensures it's treated as a value,
            // and not an executable SQL.
            $query = $this->db->conn->prepare(
                'SELECT * FROM pages WHERE subject_id = :subject_id ORDER BY position ASC'
            );
            $query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $query->execute();
            $pages = $query->fetchAll(PDO::FETCH_ASSOC);
            return $pages ?: [];
        } catch (PDOException $e) {
            error_log('Error while fetching pages by subject id: ' . $e->getMessage());
            return [];
        }
    }

    public function getPageById(int $id): ?array
    {
        try {
            // :id is a bound parameter so PDO ensures it's treated as a value,
            // and not an executable SQL.
            $query = $this->db->conn->prepare(
                'SELECT * FROM pages WHERE id = :id LIMIT 1'
            );
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            die('Error fetching page requested: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function createNewPage(
        int $subject_id,
        string $menu_name,
        int $position,
        bool $visible,
        string $content
    ): int|false {
        try {
            $query = $this->db->conn->prepare(
                'INSERT INTO pages 
                (subject_id, menu_name, position, visible, content)
                VALUES (:subject_id, :menu_name, :position, :visible, :content)'
            );
            $query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $query->bindParam(':menu_name', $menu_name, PDO::PARAM_STR);
            $query->bindParam(':position', $position, PDO::PARAM_INT);
            $query->bindParam(':visible', $visible, PDO::PARAM_BOOL);
            $query->bindParam(':content', $content, PDO::PARAM_STR);
            $query->execute();

            return $this->db->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log('Error while creating new page: ' . $e->getMessage());
            return false;
        }
    }

    public function updatePageById(
        int $id,
        string $menu_name,
        int $position,
        bool $visible,
        string $content
    ): bool {
        try {
            if ($this->getPageById($id) !== null) {
                $query = $this->db->conn->prepare(
                    'UPDATE pages
                    SET menu_name = :menu_name,
                        position = :position,
                        visible = :visible,
                        content = :content
                    WHERE id = :id'
                );
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->bindParam(':menu_name', $menu_name, PDO::PARAM_STR);
                $query->bindParam(':position', $position, PDO::PARAM_INT);
                $query->bindParam(':visible', $visible, PDO::PARAM_BOOL);
                $query->bindParam(':content', $content, PDO::PARAM_STR);
                return $query->execute();
            }
            return false;
        } catch (PDOException $e) {
            error_log('Error while updating page requested: ' . $e->getMessage());
            return false;
        }
    }

    public function deletePageById(int $id): bool
    {
        try {
            if ($this->getPageById($id) !== null) {
                $query = $this->db->conn->prepare('DELETE FROM pages WHERE id = :id');
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                return $query->execute();
            }
            return false;
        } catch (PDOException $e) {
            error_log('Error while deleting page requested: ' . $e->getMessage());
            return false;
        }
    }

    public function countPagesForSubject(int $subject_id): int
    {
        try {
            $query = $this->db->conn->prepare(
                'SELECT COUNT(*) AS count FROM pages WHERE subject_id = :subject_id'
            );
            $query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchColumn();
            return $result ? (int) $result : 0;
        } catch (PDOException $e) {
            error_log('Error while fetching count for the pages for requested subject id: ' . $e->getMessage());
            return 0;
        }
    }

    public function getFirstPositionPageBySubjectId(int $subject_id): ?array
    {
        try {
            $query = $this->db->conn->prepare(
                'SELECT * FROM pages WHERE subject_id = :subject_id ORDER BY position ASC LIMIT 1'
            );
            $query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log('Error while fetching first page for the requested subject id: ' . $e->getMessage());
            return null;
        }
    }
}
