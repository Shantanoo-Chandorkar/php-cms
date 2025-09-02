<?php

namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;
use Widget_Corps_Oops_Admin\Services\ValidationServices;

class EditSubjectController
{
    private Bootstrap $bootstrap;
    private HeaderServices $headerService;
    private NavigationServices $navigationService;
    private ValidationServices $validationService;

    public function __construct(
        Bootstrap $bootstrap,
        HeaderServices $headerService,
        NavigationServices $navigationService,
        ValidationServices $validationService
    ) {
        $this->bootstrap         = $bootstrap;
        $this->headerService     = $headerService;
        $this->navigationService = $navigationService;
        $this->validationService = $validationService;
    }

    public function index(): void
    {
        $db              = $this->bootstrap->getDB();
        $subjects        = $this->bootstrap->getSubjects();
        $selectedSubject = $this->bootstrap->getSelectedSubject();
        $subjParam       = $_GET['subj'] ?? null;
        $pageParam       = $_GET['page'] ?? null;

        if (intval($subjParam) === 0) {
            $this->redirect('content.php');
        }

        // Handle form POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = array();

            // Required + length checks
            $errors = $this->validationService->validateRequiredFields(
                array('menu_name', 'position', 'visible' )
            );
            $errors = array_merge(
                $errors,
                $this->validationService->validateMaxLengths(array('menu_name' => 30 ))
            );

            if (! empty($errors)) {
                $_SESSION['errors'] = $errors;
                $this->redirect('edit_subject.php?subj=' . urlencode($subjParam));
            }

            $menuName = $_POST['menu_name'];
            $position = (int) $_POST['position'];
            $visible  = (int) $_POST['visible'];

            $result = $db->update_subject($subjParam, $menuName, $position, $visible);

            if ($result > 0) {
                $_SESSION['message'] = 'The subject was successfully updated!';
            } else {
                $_SESSION['message'] = 'No changes were made.';
            }

            $this->redirect('edit_subject.php?subj=' . urlencode($subjParam));
        }

        // Render the template
        echo $this->headerService->getHeader('edit_subject');

        include_once __DIR__ . '/../Views/edit_subject.php';
        include_once __DIR__ . '/../../includes/footer.php';
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
