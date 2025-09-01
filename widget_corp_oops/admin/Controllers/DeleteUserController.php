<?php
namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;

class DeleteUserController
{
    private Bootstrap $_bootstrap;

    public function __construct(Bootstrap $bootstrap)
    {
        $this->_bootstrap = $bootstrap;
    }

    public function index(): void
    {
        $db = $this->_bootstrap->getDB();
        $userId = isset($_GET['user']) ? (int)$_GET['user'] : 0;

        if ($userId === 0) {
            $this->redirect("staff.php");
        }

        $result = $db->delete_user_by_id($userId);

        if ($result > 0) {
            $this->redirect("staff.php");
        } else {
            echo "<p>Failed to delete user.</p>";
            echo "<a href='content.php'>Return to the Main Page</a>";
        }
    }

    private function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
