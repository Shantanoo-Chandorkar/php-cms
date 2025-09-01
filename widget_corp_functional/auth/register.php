<?php
require_once __DIR__ . '/../helper/bootstrap.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? "");
    $password = trim($_POST['password'] ?? "");

    if ($username && $password) {
        $db = new DBConnection("widget_corp_test");
        $result = $db->register_user($username, $password);
        $message = $result['message'];
        if ($result['success']) {
            header("Location: login.php");
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
    <title>Register - Widget Corp</title>
</head>
<body>
    <h2>Register</h2>
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
        <button type="submit">Register</button>
    </form>
    <p><a href="login.php">Already have an account? Login</a></p>
</body>
</html>
<?php $db->close(); ?>