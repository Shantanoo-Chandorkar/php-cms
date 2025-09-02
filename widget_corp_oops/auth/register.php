<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Helper\Bootstrap;

use Widget_Corp_Oops_Admin\Controllers\AuthController;

$bootstrap = new Bootstrap('widget_corp_test');

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

require __DIR__ . '/../admin/Views/register.php';
