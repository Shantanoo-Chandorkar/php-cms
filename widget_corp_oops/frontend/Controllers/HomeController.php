<?php

namespace Widget_Corp_Oops_Frontend\Controllers;

use Widget_Corp_Oops_Helper\Bootstrap;
use Widget_Corp_Oops_Frontend\Services\NavigationService;

class HomeController
{
    private Bootstrap $_bootstrap;
    private NavigationService $_navService;

    public function __construct(Bootstrap $bootstrap, NavigationService $navService = null)
    {
        $this->_bootstrap  = $bootstrap;
        $this->_navService = $navService ?? new NavigationService();
    }

    public function index(?int $subjId, ?int $pageId): void
    {
        $db = $this->_bootstrap->getDB();

        $subjects = $db->get_subjects();
        $selected = $this->resolveSelection($subjId, $pageId, $db);

        $navigationHtml = $this->_navService->renderFrontendNavigation(
            $subjects,
            $selected['subject']['id'] ?? null,
            $selected['page']['id'] ?? null,
            $db
        );

        include __DIR__ . '/../Views/index.php';

        $db->close();
    }

    private function resolveSelection(?int $subjId, ?int $pageId, $db): array
    {
        $selectedSubject = null;
        $selectedPage    = null;

        if ($pageId) {
            $selectedPage = $db->get_page_by_id($pageId);
            if ($selectedPage) {
                $selectedSubject = $db->get_subject_by_id($selectedPage['subject_id']);
            }
        } elseif ($subjId) {
            $selectedSubject = $db->get_subject_by_id($subjId);
            if ($selectedSubject) {
                $selectedPage = $db->get_first_position_page_by_subject_id_with($subjId);
            }
        }

        return array(
            'subject' => $selectedSubject,
            'page'    => $selectedPage,
        );
    }
}
