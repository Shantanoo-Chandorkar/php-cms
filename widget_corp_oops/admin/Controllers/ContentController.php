<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Helper\Bootstrap;
use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;

class ContentController
{
    private Bootstrap $bootstrap;
    private HeaderServices $headerService;
    private NavigationServices $navigationService;

    public function __construct(
        Bootstrap $bootstrap,
        HeaderServices $headerService,
        NavigationServices $navigationService
    ) {
        $this->bootstrap         = $bootstrap;
        $this->headerService     = $headerService;
        $this->navigationService = $navigationService;
    }

    public function index(): void
    {
        $subjects        = $this->bootstrap->getSubjects();
        $selectedSubject = $this->bootstrap->getSelectedSubject();
        $selectedPage    = $this->bootstrap->getSelectedPage();
        $subjParam       = $_GET['subj'] ?? null;
        $pageParam       = $_GET['page'] ?? null;
        $db              = $this->bootstrap->getDB();

        // Render header
        echo $this->headerService->getHeader('content');

        // Render content view
        include __DIR__ . '/../Views/content.php';
        include_once __DIR__ . '/../Views/partials/footer.php';
    }
}
