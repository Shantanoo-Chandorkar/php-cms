<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../helper/bootstrap.php';

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Controllers\DeleteUserController;

$bootstrap = new Bootstrap('widget_corp_test');

$controller = new DeleteUserController($bootstrap);
$controller->index();
