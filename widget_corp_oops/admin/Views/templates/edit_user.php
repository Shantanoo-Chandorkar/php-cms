<h2 class="edit-user-title">Edit User</h2>

<!-- Error messages -->
<?php
$errors = $this->sessionService->get('errors') ?? [];
if (!empty($errors)) : ?>
    <div class="errors" role="alert" aria-live="assertive" tabindex="-1">
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li>
                    <?php
                        // Customize messages per field
                    switch ($error) {
                        case 'username':
                            echo 'Error updating the username.';
                            break;
                        case 'password':
                            echo 'Error updating the password.';
                            break;
                        case 'role':
                            echo 'Error updating the role.';
                            break;
                        default:
                            echo "Unknown error with $error.";
                    }
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php $this->sessionService->unset('errors'); ?>
<?php endif; ?>

<form method="post" aria-labelledby="edit-user-title">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" 
               value="<?php echo htmlspecialchars($user['username']); ?>" 
               required aria-required="true" />
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" aria-required="true" />
    </div>

    <div class="form-group">
        <label for="role">Role:</label>
        <select name="role" id="role">
            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="subscriber" <?php echo $user['role'] === 'subscriber' ? 'selected' : ''; ?>>Subscriber</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="./delete_user.php?user=<?php echo urlencode($user['id']); ?>" 
           class="btn btn-danger" 
           onclick="return confirm('Are you sure you want to delete this user?');">
           Delete User
        </a>
        <a href="staff.php" class="btn btn-info">Cancel</a>
    </div>
</form>
