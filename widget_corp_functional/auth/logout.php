<?php
require_once __DIR__ . '/../helper/bootstrap.php';

$_SESSION = [];           // clear session variables
session_destroy();        // destroy session
header("Location: login.php"); // redirect to login
exit;
