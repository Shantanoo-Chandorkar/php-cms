<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../helper/bootstrap.php';

use Widget_Corp_Oops_Helper\Bootstrap;
use Widget_Corp_Oops_Admin\Controllers\SubjectController;
use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;

$bootstrap = new Bootstrap('widget_corp_test');

$controller = new SubjectController(
    $bootstrap,
    new HeaderServices(),
    new NavigationServices(),
);

$controller->update();
