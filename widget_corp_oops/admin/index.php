<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\SessionService;

// Initialize Session
$sessionService = new SessionService();

$headerService = new HeaderServices();

// Output header.
echo $headerService->getHeader('admin_index');
?>

<div id="admin-dashboard">
    <h1>Admin Dashboard</h1>
    <nav id="admin-navigation">
        <ul>
            <li><a href="new_subject.php">Add New Subject</a></li>
            <li><a href="new_user.php">Add New User</a></li>
            <li><a href="content.php">Review Content</a></li>
            <li><a href="staff.php">Review Staff</a></li>
        </ul>
    </nav>
</div>