<?php

require_once __DIR__ . '/vendor/autoload.php';

use Widget_Corp_Oops_Frontend\Controllers\HomeController;

$subjId = isset($_GET['subj']) ? intval($_GET['subj']) : null;
$pageId = isset($_GET['page']) ? intval($_GET['page']) : null;

$controller = new HomeController();
$controller->index($subjId, $pageId);
