<?php 
require_once "helper/bootstrap.php"; 
require_once "includes/header.php"; 

$db = new DBConnection( "widget_corp_test" );

$errors = array();

if ( intval( $_GET['user'] ) === 0 ) {
    redirect_to( "staff.php" );
}

$result = $db->delete_user_by_id( $_GET['user'] );

if ( $result > 0 ) {
    redirect_to( "staff.php" );
} else {
    echo "<p>Failed to delete user.</p>";
    echo "<a href='content.php'>Return to the Main Page</a>";
}

$db->close();