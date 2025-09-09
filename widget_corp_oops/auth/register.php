<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Admin\Controllers\AuthController;
use Widget_Corp_Oops_Admin\Services\SessionService;

// Initialize Session and Redirect.
$sessionService = new SessionService();

// User Controller to access Login functionality.
$controller = new AuthController();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $result  = $controller->handleRegisterUser($username, $password);
        $message = $result['message'];
        if ($result['success']) {
            header('Location: login.php');
            exit;
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}

include_once __DIR__ . '/../admin/Views/templates/register.php';
