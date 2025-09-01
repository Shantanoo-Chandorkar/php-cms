<?php
namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;
use Widget_Corps_Oops_Admin\Services\ValidationServices;

class EditUserController
{
    private Bootstrap $_bootstrap;
    private HeaderServices $_header;
    private NavigationServices $_navigation;
    private ValidationServices $_validation;

    public function __construct(Bootstrap $bootstrap, HeaderServices $header, NavigationServices $navigation, ValidationServices $validation = new ValidationServices())
    {
        $this->_bootstrap   = $bootstrap;
        $this->_header      = $header;
        $this->_navigation  = $navigation;
        $this->_validation  = $validation;
    }

    public function index(): void
    {
        $db = $this->_bootstrap->getDB();
        $session = $this->_bootstrap->getSession();

        $userId = intval($_GET['user'] ?? 0);
        if ($userId === 0) {
            $this->redirect("content.php");
        }

        $selectedUser = $db->get_user_by_id($userId);
        if (!$selectedUser) {
            $session->set('message', "User not found.");
            $this->redirect("content.php");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($userId, $selectedUser);
            return;
        }

        // GET request → show form
        echo $this->_header->getHeader("forms");
        $user = $selectedUser;
        include __DIR__ . "/../Views/edit_user.php";
        include __DIR__ . "../../../includes/footer.php";

        $db->close();
    }

    private function handlePost(int $userId, array $selectedUser): void
    {
        $session = $this->_bootstrap->getSession();
        $db      = $this->_bootstrap->getDB();

        $errors = [];
        $required_fields = ['username', 'password', 'role'];
        $errors = $this->_validation->validateRequiredFields($required_fields);

        $field_lengths = ['username' => 50, 'password' => 255, 'role' => 20];
        $errors = array_merge($errors, $this->_validation->validateMaxLengths($field_lengths));

        if (!empty($errors)) {
            $session->set('errors', $errors);
            $this->redirect("edit_user.php?user=" . urlencode($userId));
        }

        $username = trim($_POST['username']);
        $role     = $_POST['role'];
        $password = trim($_POST['password']);

        if (!in_array($role, ['admin', 'subscriber'])) {
            $role = 'subscriber';
        }

        $hashed_password = !empty($password)
            ? password_hash($password, PASSWORD_DEFAULT)
            : $selectedUser['password'];

        $success = $db->update_user($userId, $username, $hashed_password, $role);

        $session->set('message', $success ? "User updated successfully!" : "No changes were made.");
        $this->redirect("staff.php");
    }

    public function redirect(string $url): void
    {
            header("Location: $url");
            exit;
    }
}
