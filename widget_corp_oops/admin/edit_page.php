<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Controllers\PageController;
use Widget_Corp_Oops_Admin\Services\SessionService;
use Widget_Corp_Oops_Admin\Services\SubjectService;

// Initialize session.
$sessionService = new SessionService();

$headerService     = new HeaderServices();
$navigationService = new NavigationServices();
$subjectService = new SubjectService();

$controller = new PageController(
    $sessionService,
    $subjectService,
    $headerService,
    $navigationService,
);

$pageId = intval($_GET['page'] ?? 0);
$controller->update($pageId);
