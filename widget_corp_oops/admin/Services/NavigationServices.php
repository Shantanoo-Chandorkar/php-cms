<?php

namespace Widget_Corp_Oops_Admin\Services;

use Widget_Corp_Oops_Admin\Models\Page;

class NavigationServices
{
    public function renderNavigation(array $subjects, ?int $subj_param, ?int $page_param, Page $pageModel): string
    {
        ob_start();
            include __DIR__ . '/../Views/partials/navigation.php';
        return ob_get_clean();
    }

    public function renderUsersNavigation(array $users, ?int $active_user_id): string
    {
        ob_start();
        ?>
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
        <?php
        return ob_get_clean();
    }
}
