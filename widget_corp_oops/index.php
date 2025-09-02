<?php

require_once __DIR__ . '/vendor/autoload.php';

use Widget_Corp_Oops_Helper\Bootstrap;
use Widget_Corp_Oops_Frontend\Controllers\HomeController;

$bootstrap = new Bootstrap('widget_corp_test');

$subjId = isset($_GET['subj']) ? intval($_GET['subj']) : null;
$pageId = isset($_GET['page']) ? intval($_GET['page']) : null;

$controller = new HomeController($bootstrap);
$controller->index($subjId, $pageId);
