<?php

namespace Widget_Corp_Oops_Admin\Services;

use Widget_Corp_Oops_Helper\DBConnection;

class NavigationServices
{
    public function renderNavigation(array $subjects, ?int $subj_param, ?int $page_param, DBConnection $db): string
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
                    $pages = $db->get_pages($subject['id']);
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

    public function renderFrontendNavigation(array $subjects, ?int $subj_param, ?int $page_param, DBConnection $db): string
    {
        $current_page = null;
        if (! empty($page_param)) {
            $page_id      = (int) $page_param;
            $current_page = $db->get_page_by_id($page_id);
        }

        ob_start();
        ?>
        <ul class="subjects">
            <?php if (! empty($subjects)) : ?>
                <?php
                foreach ($subjects as $subject_row) :
                    $subject_id = (int) $subject_row['id'];

                    // Subject active if subj matches OR the current page belongs to this subject.
                    $is_active_subject = ($subj_param === $subject_id)
                    || ($current_page && (int) $current_page['subject_id'] === $subject_id);

                    $subject_active_class = $is_active_subject ? ' active' : '';
                    $pages_id             = 'pages-' . $subject_id;
                    ?>
                    <li class="subject-item<?php echo $subject_active_class; ?>">
                        <button type="button"
                                class="subject-toggle"
                                data-target="<?php echo $pages_id; ?>"
                                aria-expanded="<?php echo $is_active_subject ? 'true' : 'false'; ?>">
                            <?php echo htmlspecialchars($subject_row['menu_name']); ?>
                        </button>

                        <ul id="<?php echo $pages_id; ?>" class="pages<?php echo $is_active_subject ? ' show' : ''; ?>">
                            <?php
                            $pages_for_subjects = $db->get_pages($subject_id);
                            if (! empty($pages_for_subjects)) :
                                foreach ($pages_for_subjects as $page_row) :
                                    $page_row_id       = (int) $page_row['id'];
                                    $is_active_page    = ($current_page && $page_row_id === (int) $current_page['id'])
                                                    || ($page_param === $page_row_id);
                                    $page_active_class = $is_active_page ? ' active' : '';
                                    ?>
                                    <li class="page-item<?php echo $page_active_class; ?>">
                                        <a href="<?php echo htmlspecialchars(BASE_URL . 'index.php?page=' . urlencode($page_row['id'])); ?>">
                                            <?php echo htmlspecialchars($page_row['menu_name']); ?>
                                        </a>
                                    </li>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <?php
        return ob_get_clean();
    }
}
