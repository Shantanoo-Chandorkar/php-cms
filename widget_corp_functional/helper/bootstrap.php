<?php
require_once __DIR__ . "/dbconnection.php";
require_once __DIR__ . "/../includes/functions.php";

define("BASE_URL", "/widget_corp/widget_corp_functional/");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new DBConnection("widget_corp_test");
$subjects = $db->get_subjects();

$subj_param = $_GET['subj'] ?? null;
$page_param = $_GET['page'] ?? null;

$selected_subject = null;
$selected_page    = null;

if ($page_param) {
    $selected_page = $db->get_page_by_id($page_param);
    if ($selected_page) {
        $selected_subject = $db->get_subject_by_id($selected_page['subject_id']);
    }
} elseif ($subj_param) {
    $selected_subject = $db->get_subject_by_id($subj_param);
    if ($selected_subject) {
        $selected_page = $db->get_first_position_page_by_subject_id_with($subj_param);
    }
}
