<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Helper\Bootstrap;
use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Controllers\ContentController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$bootstrap         = new Bootstrap('widget_corp_test');
$headerService     = new HeaderServices();
$navigationService = new NavigationServices();

$controller = new ContentController($bootstrap, $headerService, $navigationService);
$controller->index();
