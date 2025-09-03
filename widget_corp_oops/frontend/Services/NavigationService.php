<?php

namespace Widget_Corp_Oops_Frontend\Services;

use Widget_Corp_Oops_Helper\DBConnection;
use Widget_Corp_Oops_Admin\Models\Subject;
use Widget_Corp_Oops_Admin\Models\Page;

class NavigationService
{
    private ?Subject $subjectModel;
    private ?Page $pageModel;

    public function __construct()
    {
        $this->subjectModel = new Subject();
        $this->pageModel = new Page();
    }
    public function renderFrontendNavigation(array $subjects, ?int $subjId, ?int $pageId): string
    {
        $currentPage = null;
        if ($pageId) {
            $currentPage = $this->pageModel->getPageById($pageId);
        }

        ob_start();
        include_once __DIR__ . '/../Views/partials/navigation.php';
        return ob_get_clean();
    }
}
