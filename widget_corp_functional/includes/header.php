<?php 

function get_header( $stylesheet_name ) {
    ob_start();
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Widget Corp Content</title>
        <link href="/widget_corp/widget_corp_functional/stylesheets/<?php echo htmlspecialchars($stylesheet_name); ?>.css" 
              rel="stylesheet" media="all" type="text/css" />
    </head>
    <body>
        <div id="header" class="header">
            <h1>Widget Corp</h1>

            <div class="user-menu">
                <?php if (isset($_SESSION['username'])): ?>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    | <a style="text-decoration:none;color:aquamarine" href="/widget_corp/widget_corp_functional/auth/logout.php">Logout</a>
                <?php else: ?>
                    <a href="/widget_corp/widget_corp_functional/auth/login.php">Login</a> | 
                    <a href="/widget_corp/widget_corp_functional/auth/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>

        <div id="main" class="main">

    <?php
    return ob_get_clean();
}
