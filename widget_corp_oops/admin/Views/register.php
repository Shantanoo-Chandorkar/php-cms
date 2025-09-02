
<!DOCTYPE html>
<html>
<head>
    <title>Register - Widget Corp</title>
    <link rel="stylesheet" href="/widget_corp/widget_corp_oops/stylesheets/forms.css">
</head>
<body>
    <h2>Register</h2>
    <?php if ($message) : ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" autocomplete="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>
        <button type="submit">Register</button>
    </form>
    <p><a href="login.php">Already have an account? Login</a></p>
</body>
</html>
