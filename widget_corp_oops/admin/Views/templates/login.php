
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Widget Corp</title>
    <link rel="stylesheet" href="/widget_corp/widget_corp_oops/stylesheets/forms.css">
</head>
<body>
    <h2>Login</h2>
    <?php if ($message) : ?>
        <p><?php echo htmlspecialchars($message); ?></p>
        <?php $sessionService->unset('message'); // Clear after showing ?>
    <?php endif; ?>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" autocomplete="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p><a href="register.php">Need an account? Register</a></p>
</body>
</html>
