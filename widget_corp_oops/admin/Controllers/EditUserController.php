<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Helper\Bootstrap;
use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\ValidationServices;

class EditUserController
{
    private Bootstrap $bootstrap;
    private HeaderServices $headerServices;
    private NavigationServices $navigationServices;
    private ValidationServices $validationServices;

    public function __construct(
        Bootstrap $bootstrap,
        HeaderServices $headerServices,
        NavigationServices $navigationServices,
        ValidationServices $validationServices = new ValidationServices()
    ) {
        $this->bootstrap  = $bootstrap;
        $this->headerServices     = $headerServices;
        $this->navigationServices = $navigationServices;
        $this->validationServices = $validationServices;
    }

    public function index(): void
    {
        $db      = $this->bootstrap->getDB();
        $session = $this->bootstrap->getSession();

        $userId = intval($_GET['user'] ?? 0);
        if ($userId === 0) {
            $this->redirect('content.php');
        }

        $selectedUser = $db->get_user_by_id($userId);
        if (! $selectedUser) {
            $session->set('message', 'User not found.');
            $this->redirect('content.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($userId, $selectedUser);
            return;
        }

        // GET request → show form
        echo $this->headerServices->getHeader('forms');
        $user = $selectedUser;
        include __DIR__ . '/../Views/edit_user.php';
        include __DIR__ . '/../Views/partials/footer.php';

        $db->close();
    }

    private function handlePost(int $userId, array $selectedUser): void
    {
        $session = $this->bootstrap->getSession();
        $db      = $this->bootstrap->getDB();

        $errors          = array();
        $required_fields = array( 'username', 'password', 'role' );
        $errors          = $this->validationServices->validateRequiredFields($required_fields);

        $field_lengths = array(
            'username' => 50,
            'password' => 255,
            'role'     => 20,
        );
        $errors        = array_merge($errors, $this->validationServices->validateMaxLengths($field_lengths));

        if (! empty($errors)) {
            $session->set('errors', $errors);
            $this->redirect('edit_user.php?user=' . urlencode($userId));
        }

        $username = trim($_POST['username']);
        $role     = $_POST['role'];
        $password = trim($_POST['password']);

        if (! in_array($role, array( 'admin', 'subscriber' ))) {
            $role = 'subscriber';
        }

        $hashed_password = ! empty($password)
            ? password_hash($password, PASSWORD_DEFAULT)
            : $selectedUser['password'];

        $success = $db->update_user($userId, $username, $hashed_password, $role);

        $session->set('message', $success ? 'User updated successfully!' : 'No changes were made.');
        $this->redirect('staff.php');
    }

    public function redirect(string $url): void
    {
            header("Location: $url");
            exit;
    }
}
