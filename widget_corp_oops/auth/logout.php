<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Admin\Services\SessionService;

// Initialize sesion.
$sessionService = new SessionService();

// Destroy the session.
$sessionService->destroy();

header('Location: login.php');
exit;
