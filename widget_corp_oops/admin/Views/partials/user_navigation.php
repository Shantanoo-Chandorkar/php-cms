<ul class="users">
        <?php if (! empty($users)) : ?>
            <?php foreach ($users as $user) : ?>
                <?php $active = ($active_user_id === $user->id) ? ' active' : ''; ?>
            <li class="user-item<?php echo $active; ?>">
                <a href="edit_user.php?user=<?php echo urlencode((string) $user->id); ?>">
                    <?php echo htmlspecialchars($user->username); ?>
                    <span class="role">(<?php echo htmlspecialchars($user->role); ?>)</span>
                </a>
            </li>
            <?php endforeach; ?>
        <?php else : ?>
        <li>No staff users found.</li>
        <?php endif; ?>
</ul>
