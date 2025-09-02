<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../helper/bootstrap.php';

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Controllers\EditUserController;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;

$bootstrap = new Bootstrap('widget_corp_test');

$controller = new EditUserController(
    $bootstrap,
    new HeaderServices(),
    new NavigationServices()
);

$controller->index();
