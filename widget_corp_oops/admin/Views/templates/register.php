
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Widget Corp</title>
    <link rel="stylesheet" href="/widget_corp/widget_corp_oops/stylesheets/forms.css">
</head>
<body>
    <main>
        <header>
            <h1 class="title">Register</h1>
        </header>
        <div id="flash-message" tabindex="-1">
            <?php if (! empty($message)) : ?>
                <p id="form-error" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </p>
                <?php $sessionService->unset('message'); // Clear after showing ?>
            <?php endif; ?>
        </div>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" autocomplete="username" required aria-describedby="form-error"><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>
            <button type="submit">Register</button>
        </form>
        <p><a href="login.php">Already have an account? Login</a></p>
    </main>
    <script src="/widget_corp/widget_corp_oops/javascripts/rl_focus.js" defer></script>
</body>
</html>
