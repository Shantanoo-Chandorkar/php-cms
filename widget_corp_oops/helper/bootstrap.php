<?php

namespace Widget_Corp_Oops_Helper;

use Widget_Corp_Oops_Admin\Services\SubjectService;

class Bootstrap
{
    private DBConnection $db;
    private SessionService $session;
    private SubjectService $subjectService;

    private array $subjects         = array();
    private ?array $selectedSubject = null;
    private ?array $selectedPage    = null;

    public function __construct(string $dbname)
    {
        $this->session        = new SessionService();
        $this->db             = new DBConnection($dbname);
        $this->subjectService = new SubjectService($this->db);

        $this->initNavigation();
    }

    private function initNavigation(): void
    {
        $this->subjects = $this->subjectService->getSubjects();

        $subjParam = isset($_GET['subj']) ? (int) $_GET['subj'] : null;
        $pageParam = isset($_GET['page']) ? (int) $_GET['page'] : null;

        $resolved = $this->subjectService->resolveSelection($subjParam, $pageParam);

        $this->selectedSubject = $resolved['selected_subject'];
        $this->selectedPage    = $resolved['selected_page'];
    }

    public function getDB(): DBConnection
    {
        return $this->db;
    }

    public function getSession(): SessionService
    {
        return $this->session;
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
}
