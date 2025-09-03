<?php

namespace Widget_Corp_Oops_Admin\Services;

use Widget_Corp_Oops_Helper\DBConnection;
use Widget_Corp_Oops_Admin\Models\Page;

class NavigationServices
{
    public function renderNavigation(array $subjects, ?int $subj_param, ?int $page_param, Page $pageModel): string
    {
        ob_start(); ?>
        <ul class="subjects">
            <?php foreach ($subjects as $subject) : ?>
                <?php $active_subject = ($subj_param === $subject['id']) ? ' active' : ''; ?>
                <li class="subject-item<?php echo $active_subject; ?>">
                    <a href="edit_subject.php?subj=<?php echo urlencode((string) $subject['id']); ?>">
                        <?php echo htmlspecialchars($subject['menu_name']); ?>
                    </a>
                    <?php
                    $pages = $pageModel->getPagesBySubjectId($subject['id']);
                    if ($pages) :
                        ?>
                        <ul class="pages">
                            <?php foreach ($pages as $page) : ?>
                                <?php $active_page = ($page_param === $page['id']) ? ' active' : ''; ?>
                                <li class="page-item<?php echo $active_page; ?>">
                                    <a href="edit_page.php?page=<?php echo urlencode((string) $page['id']); ?>">
                                        <?php echo htmlspecialchars($page['menu_name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
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
