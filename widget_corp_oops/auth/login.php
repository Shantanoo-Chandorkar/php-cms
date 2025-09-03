<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Admin\Controllers\AuthController;
use Widget_Corp_Oops_Admin\Services\SessionService;

// Initialize Session.
$sessionService = new SessionService();

// Auth Controller to access Login functionality.
$controller = new AuthController();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');


    if ($username && $password) {
        $result  = $controller->handleLoginUser($username, $password);
        $message = $result['message'];
        if ($result['success']) {
            $sessionService->set('username', $username);
            header('Location: ../admin/index.php');
            exit;
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}

include_once __DIR__ . '/../admin/Views/templates/login.php';
