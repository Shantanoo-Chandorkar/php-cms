<?php

namespace Widget_Corps_Oops_Admin\Services;

class HeaderServices
{
    public function render(string $stylesheetName): string
    {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Widget Corp Content</title>
            <link href="/widget_corp/widget_corp_oops/stylesheets/<?php echo htmlspecialchars($stylesheetName); ?>.css"
                    rel="stylesheet" media="all" type="text/css" />
        </head>
        <body>
        <div id="header" class="header">
            <h1>Widget Corp</h1>
            <div class="user-menu">
                <?php if (isset($_SESSION['username'])) : ?>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    | <a href="/widget_corp/widget_corp_oops/auth/logout.php"
                        style="text-decoration:none;color:aquamarine">Logout</a>
                <?php else : ?>
                    <a href="/widget_corp/widget_corp_oops/auth/login.php">Login</a> | 
                    <a href="/widget_corp/widget_corp_oops/auth/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
        <div id="main" class="main">
        <?php
        return ob_get_clean();
    }

    public function getHeader(string $stylesheetName): string
    {
        $baseUrl = $this->getSiteURL();

        ob_start();
        ?>
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
                    | <a href="/widget_corp/widget_corp_oops/auth/logout.php"
                        style="text-decoration:none;color:aquamarine">Logout</a>
                <?php else : ?>
                    <a href="/widget_corp/widget_corp_oops/auth/login.php">Login</a> | 
                    <a href="/widget_corp/widget_corp_oops/auth/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
        <div id="main" class="main">
        <?php
        return ob_get_clean();
    }

    function getSiteURL(): string
    {
        $protocol = ( ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ) ? 'https' : 'http';
        $host     = $_SERVER['HTTP_HOST'];

        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        $scriptPath = str_replace('/admin', '', $scriptPath); // remove /admin
        $scriptPath = rtrim($scriptPath, '/\\');

        return $protocol . '://' . $host . $scriptPath . '/';
    }
}
