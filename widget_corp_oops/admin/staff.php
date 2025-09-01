<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;
use Widget_Corps_Oops_Admin\Controllers\StaffController;
use Widget_Corps_Oops_Helper\DBConnection;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new DBConnection("widget_corp_test");
$header = new HeaderServices();
$navigation = new NavigationServices();

$controller = new StaffController($db, $header, $navigation);
$controller->index();
