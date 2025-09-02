<?php

namespace Widget_Corp_Oops_Admin\Services;

use Widget_Corp_Oops_Helper\DBConnection;

class SubjectService
{
    private DBConnection $db;

    public function __construct(DBConnection $db)
    {
        $this->db = $db;
    }

    public function getSubjects(): array
    {
        return $this->db->get_subjects();
    }

    public function resolveSelection(?int $subjId, ?int $pageId): array
    {
        $selectedSubject = null;
        $selectedPage    = null;

        if ($pageId) {
            $selectedPage = $this->db->get_page_by_id($pageId) ?: null;
            if ($selectedPage !== null) {
                $selectedSubject = $this->db->get_subject_by_id($selectedPage['subject_id']) ?: null;
            }
        } elseif ($subjId) {
            $selectedSubject = $this->db->get_subject_by_id($subjId) ?: null;
            if ($selectedSubject !== null) {
                $selectedPage = $this->db->get_first_position_page_by_subject_id_with($subjId) ?: null;
            }
        }

        return array(
            'selected_subject' => $selectedSubject,
            'selected_page'    => $selectedPage,
        );
    }
}
