<!DOCTYPE html>
<html>
<head>
    <title>Widget Corp Content</title>
    <link href="<?php echo $baseUrl; ?>stylesheets/<?php echo htmlspecialchars($stylesheetName); ?>.css"
        rel="stylesheet" media="all" type="text/css" />
</head>
<body>
<div id="header" class="header">
    <h1>Widget Corp</h1>
    <div class="user-menu">
        <?php if (isset($_SESSION['username'])) : ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            | <a href="../admin/staff.php"
                style="text-decoration:none;color:aquamarine">Manage Staff</a>
            | <a href="../auth/logout.php"
                style="text-decoration:none;color:aquamarine">Logout</a>
        <?php else : ?>
            <a href="/widget_corp/widget_corp_oops/auth/login.php">Login</a> | 
            <a href="/widget_corp/widget_corp_oops/auth/register.php">Register</a>
        <?php endif; ?>
    </div>
</div>
<div id="main" class="main">
