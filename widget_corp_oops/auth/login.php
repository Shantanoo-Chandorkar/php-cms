<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Helper\Bootstrap;
use Widget_Corp_Oops_Admin\Controllers\AuthController;

$bootstrap = new Bootstrap('widget_corp_test');
$session   = $bootstrap->getSession();

// User Controller to access Login functionality.
$controller = new AuthController();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');


    if ($username && $password) {
        $result  = $controller->handleLoginUser($username, $password);
        $message = $result['message'];
        if ($result['success']) {
            $session->set('username', $username);
            header('Location: ../admin/staff.php');
            exit;
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}

include __DIR__ . '/../admin/Views/login.php';
