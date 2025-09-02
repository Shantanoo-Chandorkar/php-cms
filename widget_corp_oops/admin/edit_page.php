<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Helper\Bootstrap;
use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\ValidationServices;
use Widget_Corp_Oops_Admin\Controllers\PageController;

$bootstrap         = new Bootstrap('widget_corp_test');
$headerService     = new HeaderServices();
$navigationService = new NavigationServices();
$validationService = new ValidationServices();

$controller = new PageController(
    $bootstrap,
    $headerService,
    $navigationService,
    $validationService
);

$pageId = intval($_GET['page'] ?? 0);
$controller->update($pageId);
