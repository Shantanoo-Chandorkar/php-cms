<?php
require_once __DIR__ . '/../helper/bootstrap.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? "");
    $password = trim($_POST['password'] ?? "");

    if ($username && $password) {
        $db = new DBConnection("widget_corp_test");
        $result = $db->login_user($username, $password);
        $message = $result['message'];
        if ($result['success']) {
            header("Location: ../content.php");
            exit;
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Widget Corp</title>
</head>
<body>
    <h2>Login</h2>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Username:
            <input type="text" name="username" required>
        </label><br>
        <label>Password:
            <input type="password" name="password" required>
        </label><br>
        <button type="submit">Login</button>
    </form>
    <p><a href="register.php">Need an account? Register</a></p>
</body>
</html>
<?php $db->close(); ?>