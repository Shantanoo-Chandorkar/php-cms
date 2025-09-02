<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Helper\Bootstrap;

$bootstrap = new Bootstrap('widget_corp_test');
$session   = $bootstrap->getSession();

$session->destroy();   // clear and destroy session

header('Location: login.php');
exit;
