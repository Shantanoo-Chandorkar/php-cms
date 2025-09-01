<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../helper/bootstrap.php';

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Controllers\EditSubjectController;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;
use Widget_Corps_Oops_Admin\Services\ValidationServices;

// instantiate Bootstrap with database
$bootstrap = new Bootstrap( 'widget_corp_test' );


$controller = new EditSubjectController(
    $bootstrap,
    new HeaderServices(),
    new NavigationServices(),
    new ValidationServices()
);

$controller->index();
