<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Helper\Bootstrap;
use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\ValidationServices;
use Widget_Corp_Oops_Admin\Models\Subject;
use Widget_Corp_Oops_Admin\Services\RedirectService;

class SubjectController
{
    private Bootstrap $bootstrap;
    private HeaderServices $headerService;
    private NavigationServices $navigationServices;
    private ValidationServices $validationService;
    private Subject $subjectModel;
    private RedirectService $redirectService;

    public function __construct(
        Bootstrap $bootstrap,
        HeaderServices $headerService,
        NavigationServices $navigationServices,
        ValidationServices $validationService = new ValidationServices(),
    ) {
        $this->bootstrap           = $bootstrap;
        $this->headerService     = $headerService;
        $this->navigationServices = $navigationServices;
        $this->validationService          = $validationService;
        $this->subjectModel       = new Subject();
        $this->redirectService     = new RedirectService();
    }

    public function create(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $this->handleFormSubmission();
            return;
        }

        $subjects         = $this->bootstrap->getSubjects();
        $selected_subject = $this->bootstrap->getSelectedSubject();
        $selected_page    = $this->bootstrap->getSelectedPage();

        // We need to remove this line after refactoring.
        $db               = $this->bootstrap->getDB();

        echo $this->headerService->getHeader('edit_subject');

        // View for the new subject forms.
        include __DIR__ . '/../Views/new_subject.php';
        include __DIR__ . '/../Views/partials/footer.php';
    }


    public function handleFormSubmission(): void
    {
        $errors          = [];
        $required_fields = ['menu_name', 'position', 'visible'];
        $errors          = $this->validationService->validateRequiredFields($required_fields);

        $field_lengths = ['menu_name' => 30];
        $errors        = array_merge($errors, $this->validationService->validateMaxLengths($field_lengths));

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirectService->redirect('new_subject.php');
        }

        $menu_name = $_POST['menu_name'];
        $position  = $_POST['position'];
        $visible   = $_POST['visible'];

        $new_id = $this->subjectModel->createNewSubject($menu_name, $position, $visible);

        if ($new_id) {
            $this->redirectService->redirect('content.php');
        } else {
            $_SESSION['errors'] = ['Subject with this name already exists.'];
            $this->redirectService->redirect('new_subject.php');
        }
    }

    public function update(): void
    {
        $db              = $this->bootstrap->getDB();
        $subjects        = $this->bootstrap->getSubjects();
        $selectedSubject = $this->bootstrap->getSelectedSubject();
        $subjParam       = $_GET['subj'] ?? null;
        $pageParam       = $_GET['page'] ?? null;

        if (intval($subjParam) === 0) {
            $this->redirectService->redirect('content.php');
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
                $this->redirectService->redirect('edit_subject.php?subj=' . urlencode($subjParam));
            }

            $menuName = $_POST['menu_name'];
            $position = (int) $_POST['position'];
            $visible  = (int) $_POST['visible'];

            $result = $this->subjectModel->updateSubject($subjParam, $menuName, $position, $visible);

            if ($result > 0) {
                $_SESSION['message'] = 'The subject was successfully updated!';
            } else {
                $_SESSION['message'] = 'No changes were made.';
            }

            $this->redirectService->redirect('edit_subject.php?subj=' . urlencode($subjParam));
        }

        // Render the template
        echo $this->headerService->getHeader('edit_subject');

        include_once __DIR__ . '/../Views/edit_subject.php';
        include __DIR__ . '/../Views/partials/footer.php';
    }

    public function delete(): void
    {
        $subjectId = isset($_GET['subj']) ? (int) $_GET['subj'] : 0;

        if ($subjectId === 0) {
            $this->redirectService->redirect('content.php');
        }

        $result = $this->subjectModel->deleteSubjectById($subjectId);

        if ($result > 0) {
            $this->redirectService->redirect('content.php');
        } else {
            echo '<p>Failed to delete subject.</p>';
            echo "<a href='content.php'>Return to the Main Page</a>";
        }
    }
}
