<h2 id="new-user-title" class="new-user-title">Add New User</h2>

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
                            echo 'Username is required.';
                            break;
                        case 'password':
                            echo 'Password is required.';
                            break;
                        case 'role':
                            echo 'Role is required.';
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

<form action="new_user.php" method="post" aria-labelledby="new-user-title" class="form-subject">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" maxlength="30" required aria-required="true" />
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required aria-required="true" />
    </div>

    <div class="form-group">
        <label for="role">Role:</label>
        <select name="role" id="role">
            <option value="admin">Admin</option>
            <option value="subscriber" selected>Subscriber</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="staff.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>
