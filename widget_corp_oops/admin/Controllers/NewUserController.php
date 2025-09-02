<?php

namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;
use Widget_Corps_Oops_Admin\Services\ValidationServices;

class NewUserController
{
    private Bootstrap $_bootstrap;
    private HeaderServices $_header;
    private NavigationServices $_navigation;
    private ValidationServices $_validation;

    public function __construct(Bootstrap $bootstrap, HeaderServices $header, NavigationServices $navigation, ValidationServices $validation = new ValidationServices())
    {
        $this->_bootstrap  = $bootstrap;
        $this->_header     = $header;
        $this->_navigation = $navigation;
        $this->_validation = $validation;
    }

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
            return;
        }

        echo $this->_header->getHeader('forms');
        require __DIR__ . '/../Views/new_user.php';
        require __DIR__ . '../../../includes/footer.php';

        $this->_bootstrap->getDB()->close();
    }

    private function handlePost(): void
    {
        $db      = $this->_bootstrap->getDB();
        $session = $this->_bootstrap->getSession();

        $errors          = array();
        $required_fields = array( 'username', 'password', 'role' );
        $errors          = $this->_validation->validateRequiredFields($required_fields);

        $field_lengths = array(
            'username' => 50,
            'password' => 255,
            'role'     => 20,
        );
        $errors        = array_merge($errors, $this->_validation->validateMaxLengths($field_lengths));

        if (! empty($errors)) {
            $session->set('errors', $errors);
            $this->redirect('new_user.php');
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $role     = $_POST['role'];

        if (! in_array($role, array( 'admin', 'subscriber' ))) {
            $role = 'subscriber';
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $new_user_id     = $db->create_new_user($username, $hashed_password, $role);

        if ($new_user_id) {
            $this->redirect('staff.php');
        } else {
            $session->set('message', 'User already exists.');
            $this->redirect('new_user.php');
        }
    }

    public function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
