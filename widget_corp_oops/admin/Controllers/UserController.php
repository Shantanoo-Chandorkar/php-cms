<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\ValidationServices;
use Widget_Corp_Oops_Admin\Services\RedirectService;
use Widget_Corp_Oops_Admin\Services\SessionService;
use Widget_Corp_Oops_Admin\Models\User;

class UserController
{
    private HeaderServices $headerServices;
    private NavigationServices $navigationServices;
    private ValidationServices $validationServices;
    private RedirectService $redirectService;
    private SessionService $sessionService;
    private User $userModel;

    public function __construct(
        SessionService $sessionService,
        HeaderServices $headerServices,
        NavigationServices $navigationServices,
        ValidationServices $validationServices = new ValidationServices()
    ) {
        $this->sessionService  = $sessionService;
        $this->headerServices     = $headerServices;
        $this->navigationServices = $navigationServices;
        $this->validationServices = $validationServices;
        $this->userModel         = new User();
        $this->redirectService    = new RedirectService();
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreateUser();
            return;
        }

        echo $this->headerServices->getHeader('forms');
        include_once __DIR__ . '/../Views/templates/new_user.php';
        include_once __DIR__ . '/../Views/partials/footer.php';
    }

    private function handleCreateUser(): void
    {

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
            $this->sessionService->set('errors', $errors);
            $this->redirectService->redirect('new_user.php');
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $role     = $_POST['role'];

        if (! in_array($role, array( 'admin', 'subscriber' ))) {
            $role = 'subscriber';
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $new_user_id     = $this->userModel->createNewUser($username, $hashed_password, $role);

        if ($new_user_id) {
            $this->redirectService->redirect('staff.php');
        } else {
            $this->sessionService->set('message', 'User already exists.');
            $this->redirectService->redirect('new_user.php');
        }
    }

    public function update(): void
    {
        $userId = intval($_GET['user'] ?? 0);
        if ($userId === 0) {
            $this->redirectService->redirect('content.php');
        }

        $selectedUser = $this->userModel->getUserById($userId);
        if (! $selectedUser) {
            $this->sessionService->set('message', 'User not found.');
            $this->redirectService->redirect('content.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUpdateUser($userId, $selectedUser);
            return;
        }

        // GET request → show form
        echo $this->headerServices->getHeader('forms');
        $user = $selectedUser;
        include_once __DIR__ . '/../Views/templates/edit_user.php';
        include_once __DIR__ . '/../Views/partials/footer.php';
    }

    private function handleUpdateUser(int $userId, array $selectedUser): void
    {
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
            $this->sessionService->set('errors', $errors);
            $this->redirectService->redirect('edit_user.php?user=' . urlencode($userId));
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

        $success = $this->userModel->updateUser($userId, $username, $hashed_password, $role);

        $this->sessionService->set('message', $success ? 'User updated successfully!' : 'No changes were made.');
        $this->redirectService->redirect('staff.php');
    }

    public function delete(): void
    {
        $userId = isset($_GET['user']) ? (int) $_GET['user'] : 0;

        if ($userId === 0) {
            $this->redirectService->redirect('staff.php');
        }

        $result = $this->userModel->deleteUserById($userId);

        if ($result > 0) {
            $this->redirectService->redirect('staff.php');
        } else {
            echo '<p>Failed to delete user.</p>';
            echo "<a href='content.php'>Return to the Main Page</a>";
        }
    }
}
