<h2>Edit User</h2>
<form method="post">
    <p>Username: <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']) ?>"></p>
    <p>Password: <input type="password" name="password"></p>
    <p>Role:
        <select name="role">
            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="subscriber" <?php echo $user['role'] === 'subscriber' ? 'selected' : '' ?>>Subscriber</option>
        </select>
    </p>
    <button type="submit">Update User</button>
    <a href="./delete_user.php?user=<?php echo urlencode($user['id']); ?>" class="btn-danger">Delete User</a>
</form>
