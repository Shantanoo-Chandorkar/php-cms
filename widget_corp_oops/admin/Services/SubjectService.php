<?php

namespace Widget_Corp_Oops_Admin\Services;

use Widget_Corp_Oops_Admin\Models\Subject;
use Widget_Corp_Oops_Admin\Models\Page;

class SubjectService
{
    private ?Subject $subjectModel;
    private ?Page $pageModel;
    private array $subjects;
    private ?array $selectedSubject;
    private ?array $selectedPage;

    public function __construct()
    {
        $this->subjectModel = new Subject();
        $this->pageModel = new Page();
        $this->loadSubjects();
        $this->initializeNavigation();
    }

    public function loadSubjects(): void
    {
        $this->subjects = $this->subjectModel->getSubjects();
    }

    public function initializeNavigation(): void
    {
        $subjParam = isset($_GET['subj']) ? (int) $_GET['subj'] : null;
        $pageParam = isset($_GET['page']) ? (int) $_GET['page'] : null;

        $resolved = $this->resolveSelection($subjParam, $pageParam);
        $this->selectedSubject = $resolved['selected_subject'];
        $this->selectedPage = $resolved['selected_page'];
    }

    public function getSubjects(): array
    {
        return $this->subjects;
    }

    public function getSelectedSubject(): ?array
    {
        return $this->selectedSubject;
    }

    public function getSelectedPage(): ?array
    {
        return $this->selectedPage;
    }

    public function resolveSelection(?int $subjId, ?int $pageId): array
    {
        $selectedSubject = null;
        $selectedPage    = null;

        if ($pageId) {
            $selectedPage = $this->pageModel->getPageById($pageId) ?: null;
            if ($selectedPage !== null) {
                $selectedSubject = $this->subjectModel->getSubjectById($selectedPage['subject_id']) ?: null;
            }
        } elseif ($subjId) {
            $selectedSubject = $this->subjectModel->getSubjectById($subjId) ?: null;
            if ($selectedSubject !== null) {
                $selectedPage = $this->pageModel->getFirstPositionPageBySubjectId($subjId) ?: null;
            }
        }

        return [
            'selected_subject' => $selectedSubject,
            'selected_page'    => $selectedPage,
        ];
    }
}
