<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Admin\Controllers\UserController;
use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\SessionService;
use Widget_Corp_Oops_Admin\Services\RedirectService;

// Initialize Session and Redirect.
$sessionService = new SessionService();
$redirectService = new RedirectService();

$redirectService->redirectGueststoLogin($sessionService);

$controller = new UserController(
    $sessionService,
    new HeaderServices(),
    new NavigationServices()
);

$controller->update();
