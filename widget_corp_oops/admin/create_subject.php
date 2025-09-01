<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../helper/bootstrap.php';

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Controllers\CreateSubjectController;
use Widget_Corps_Oops_Admin\Services\ValidationServices;

$bootstrap = new Bootstrap( 'widget_corp_test' );

$controller = new CreateSubjectController(
    $bootstrap,
    new ValidationServices()
);

$controller->index();
