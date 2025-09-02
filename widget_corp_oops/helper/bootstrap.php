<?php

namespace Widget_Corps_Oops_Helper;

use Widget_Corps_Oops_Admin\Services\SubjectService;

class Bootstrap
{
    private DBConnection $_db;
    private SessionService $_session;
    private SubjectService $_subjectService;

    private array $_subjects         = array();
    private ?array $_selectedSubject = null;
    private ?array $_selectedPage    = null;

    public function __construct(string $dbname)
    {
        $this->_session        = new SessionService();
        $this->_db             = new DBConnection($dbname);
        $this->_subjectService = new SubjectService($this->_db);

        $this->initNavigation();
    }

    private function initNavigation(): void
    {
        $this->_subjects = $this->_subjectService->getSubjects();

        $subjParam = isset($_GET['subj']) ? (int) $_GET['subj'] : null;
        $pageParam = isset($_GET['page']) ? (int) $_GET['page'] : null;

        $resolved = $this->_subjectService->resolveSelection($subjParam, $pageParam);

        $this->_selectedSubject = $resolved['selected_subject'];
        $this->_selectedPage    = $resolved['selected_page'];
    }

    public function getDB(): DBConnection
    {
        return $this->_db;
    }

    public function getSession(): SessionService
    {
        return $this->_session;
    }

    public function getSubjects(): array
    {
        return $this->_subjects;
    }

    public function getSelectedSubject(): ?array
    {
        return $this->_selectedSubject;
    }

    public function getSelectedPage(): ?array
    {
        return $this->_selectedPage;
    }
}
