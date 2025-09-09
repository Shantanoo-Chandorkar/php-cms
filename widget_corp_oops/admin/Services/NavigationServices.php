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
            include __DIR__ . '/../Views/partials/user_navigation.php';
        return ob_get_clean();
    }
}
