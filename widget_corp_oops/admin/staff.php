<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Controllers\StaffController;
use Widget_Corp_Oops_Admin\Services\SessionService;


// Initialize Session.
$sessionService = new SessionService();

$headerServices     = new HeaderServices();
$navigationServices = new NavigationServices();

$controller = new StaffController($headerServices, $navigationServices);
$controller->index();
