<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Admin\Controllers\UserController;
use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\SessionService;

// Initialize Session.
$sessionService = new SessionService();

$controller = new UserController(
    $sessionService,
    new HeaderServices(),
    new NavigationServices()
);

$controller->update();
