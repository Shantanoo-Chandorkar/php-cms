<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\SessionService;
use Widget_Corp_Oops_Admin\Services\SubjectService;
use Widget_Corp_Oops_Admin\Services\RedirectService;
use Widget_Corp_Oops_Admin\Controllers\ContentController;

// Initialize Session and Redirect.
$sessionService = new SessionService();
$redirectService = new RedirectService();

$redirectService->redirectGueststoLogin($sessionService);

// Other Services
$headerService     = new HeaderServices();
$navigationService = new NavigationServices();
$subjectService = new SubjectService();


$controller = new ContentController($headerService, $navigationService, $subjectService);
$controller->index();
