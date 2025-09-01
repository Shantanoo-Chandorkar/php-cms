<?php 
require_once "helper/bootstrap.php"; 
require_once "includes/header.php"; 

$db = new DBConnection( "widget_corp_test" );

$errors = array();

if ( intval( $_GET['page'] ) === 0 ) {
    redirect_to( "content.php" );
}

$result = $db->delete_page_by_id( $_GET['page'] );

if ( $result > 0 ) {
    redirect_to( "content.php" );
} else {
    echo "<p>Failed to delete page.</p>";
    echo "<a href='content.php'>Return to the Main Page</a>";
}

$db->close();