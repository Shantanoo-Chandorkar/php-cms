<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\SubjectService;
use Widget_Corp_Oops_Admin\Services\SessionService;
use Widget_Corp_Oops_Admin\Models\Page;

class ContentController
{
    private HeaderServices $headerService;
    private NavigationServices $navigationService;
    private SubjectService $subjectService;
    private SessionService $sessionService;

    public function __construct(
        HeaderServices $headerService,
        NavigationServices $navigationService,
        SubjectService $subjectService
    ) {
        $this->headerService = $headerService;
        $this->navigationService = $navigationService;
        $this->subjectService = $subjectService;
        $this->sessionService = new SessionService();
    }

    public function index(): void
    {
        // Get data from subject service
        $subjects = $this->subjectService->getSubjects();
        $selectedSubject = $this->subjectService->getSelectedSubject();
        $selectedPage = $this->subjectService->getSelectedPage();

        // Get URL parameters
        $subjParam = $_GET['subj'] ?? null;
        $pageParam = $_GET['page'] ?? null;

        // Create page model for navigation service
        $pageModel = new Page();

        // Render header
        echo $this->headerService->getHeader('content', $this->sessionService);

        // Render content view
        include_once __DIR__ . '/../Views/templates/content.php';
        include_once __DIR__ . '/../Views/partials/footer.php';
    }
}
