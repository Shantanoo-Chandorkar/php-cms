<?php

// Prevent direct access
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    http_response_code(403);
    exit('Access denied.');
}

// Define the constants
define('SERVER_NAME', 'localhost');
define('USER_NAME', 'root');
define('PASSWORD', 'Shantanoo@Mysql');
