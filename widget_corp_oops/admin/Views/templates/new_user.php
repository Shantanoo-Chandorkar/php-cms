<h2 class="title">Add New User</h2>

<?php
// show validation errors if any.
if (! empty($this->sessionService->get('errors') ?? array())) : ?>
    <div class="errors">
        <ul>
            <?php foreach ($this->sessionService->get('errors') as $error) : ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php $this->sessionService->unset('errors'); ?>
<?php endif; ?>

<?php
// show messages if any
if (! empty($this->sessionService->get('message') ?? '')) :
    ?>
    <div class="message">
        <?php echo htmlspecialchars($this->sessionService->get('message')); ?>
    </div>
    <?php $this->sessionService->unset('message'); ?>
<?php endif; ?>

<form action="new_user.php" method="post">
    <p>
        <label for="username">Username:</label><br>
        <input type="text" name="username" id="username" maxlength="30" required>
    </p>

    <p>
        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required>
    </p>

    <p>
        <label for="role">Role:</label><br>
        <select name="role" id="role">
            <option value="admin">Admin</option>
            <option value="subscriber" selected>Subscriber</option>
        </select>
    </p>

    <p>
        <button type="submit">Create User</button>
    </p>
</form>

<p><a href="staff.php">Cancel</a></p>
