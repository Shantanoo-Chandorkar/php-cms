<?php
// require_once "helper/dbconnection.php";
require_once "includes/functions.php";

use Widget_Corps_Oops_Helper\DBConnection;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new DBConnection("widget_corp_test");

$errors = array();

// Required fields
$required_fields = ['menu_name', 'position', 'visible'];
$errors = validate_required_fields($required_fields);

// Length restrictions
$field_lengths = ['menu_name' => 30];
$errors = array_merge($errors, validate_max_lengths($field_lengths));

if ( !empty($errors) ) {
    redirect_to("new_subject.php");
}

$menu_name = $_POST['menu_name'];
$position = $_POST['position'];
$visible = $_POST['visible'];

$new_id = $db->create_new_subject( $menu_name, $position, $visible );
echo "New Id: " . $new_id;
if ( $new_id ) {
    redirect_to( "content.php" );
} else {
    $_SESSION['errors'] = ["Subject with this name already exists."];
    redirect_to("new_subject.php");
}

$db->close();?>
