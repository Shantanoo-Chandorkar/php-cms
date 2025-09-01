<?php 
require_once "helper/bootstrap.php"; 
require_once "includes/header.php"; 

$db = new DBConnection( "widget_corp_test" );

$errors = array();

if ( intval( $_GET['subj'] ) === 0 ) {
    redirect_to( "content.php" );
}

$result = $db->delete_subject_by_id( $_GET['subj'] );

if ( $result > 0 ) {
    redirect_to( "content.php" );
} else {
    echo "<p>Failed to delete subject.</p>";
    echo "<a href='content.php'>Return to the Main Page</a>";
}

$db->close();