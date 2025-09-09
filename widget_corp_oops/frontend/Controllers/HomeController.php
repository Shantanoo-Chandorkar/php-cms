<?php

namespace Widget_Corp_Oops_Frontend\Controllers;

use Widget_Corp_Oops_Frontend\Services\NavigationService;
use Widget_Corp_Oops_Admin\Models\Subject;
use Widget_Corp_Oops_Admin\Models\Page;

class HomeController
{
    private NavigationService $navigationService;
    private ?Subject $subjectModel;
    private ?Page $pageModel;

    public function __construct(
        ?NavigationService $navService = null,
        ?Subject $subject = null,
        ?Page $page = null
    ) {
        $this->navigationService = $navService ?? new NavigationService();
        $this->subjectModel = $subject ?? new Subject();
        $this->pageModel = $page ?? new Page();
    }


    public function index(?int $subjId, ?int $pageId): void
    {
        $subjects = $this->subjectModel->getSubjects();
        $selected = $this->resolveSelection($subjId, $pageId);

        $navigationHtml = $this->navigationService->renderFrontendNavigation(
            $subjects,
            $selected['subject']['id'] ?? null,
            $selected['page']['id'] ?? null,
        );

        include_once __DIR__ . '/../Views/index.php';
    }

    private function resolveSelection(?int $subjId, ?int $pageId): array
    {
        $selectedSubject = null;
        $selectedPage    = null;

        if ($pageId) {
            $selectedPage = $this->pageModel->getPageById($pageId);
            if ($selectedPage) {
                $selectedSubject = $this->subjectModel->getSubjectById($selectedPage['subject_id']);
            }
        } elseif ($subjId) {
            $selectedSubject = $this->subjectModel->getSubjectById($subjId);
            if ($selectedSubject) {
                $selectedPage = $this->pageModel->getFirstPositionPageBySubjectId($subjId);
            }
        }

        return array(
            'subject' => $selectedSubject,
            'page'    => $selectedPage,
        );
    }
}
