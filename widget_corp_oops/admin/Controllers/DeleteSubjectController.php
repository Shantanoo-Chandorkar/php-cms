<?php

namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;

class DeleteSubjectController
{
    private Bootstrap $_bootstrap;

    public function __construct(Bootstrap $bootstrap)
    {
        $this->_bootstrap = $bootstrap;
    }

    public function index(): void
    {
        $db        = $this->_bootstrap->getDB();
        $subjectId = isset($_GET['subj']) ? (int) $_GET['subj'] : 0;

        if ($subjectId === 0) {
            $this->redirect('content.php');
        }

        $result = $db->delete_subject_by_id($subjectId);

        if ($result > 0) {
            $this->redirect('content.php');
        } else {
            echo '<p>Failed to delete subject.</p>';
            echo "<a href='content.php'>Return to the Main Page</a>";
        }
    }

    private function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
