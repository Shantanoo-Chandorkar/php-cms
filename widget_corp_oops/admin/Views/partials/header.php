<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Widget Corp Content</title>
        <link href="<?php echo $baseUrl; ?>stylesheets/admin.css" rel="stylesheet" media="all" type="text/css" />
        <link href="<?php echo $baseUrl; ?>stylesheets/<?php echo htmlspecialchars($stylesheetName); ?>.css"
            rel="stylesheet" media="all" type="text/css" />
    </head>
    <body>
        <a href="#main" class="skip-link">Skip to main content</a>
        <header id="header" class="header">
            <h1>Widget Corp</h1>
            <div class="user-menu" aria-label="User menu">
                <?php if (isset($_SESSION['username'])) : ?>
                    <span id="user-greeting">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <a href="../admin/staff.php" aria-describedby="user-greeting">Manage Staff</a>
                    <a href="../auth/logout.php">Logout</a>
                <?php else : ?>
                    <a href="/auth/login.php">Login</a>
                    <a href="/auth/register.php">Register</a>
                <?php endif; ?>
            </div>

        </header>
        <main id="main" class="main">
