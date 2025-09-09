<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\ValidationServices;
use Widget_Corp_Oops_Admin\Services\RedirectService;
use Widget_Corp_Oops_Admin\Services\SessionService;
use Widget_Corp_Oops_Admin\Services\SubjectService;
use Widget_Corp_Oops_Admin\Models\Subject;
use Widget_Corp_Oops_Admin\Models\Page;

class SubjectController
{
    private HeaderServices $headerService;
    private NavigationServices $navigationServices;
    private ValidationServices $validationService;
    private Subject $subjectModel;
    private RedirectService $redirectService;
    private SessionService $sessionService;
    private SubjectService $subjectService;

    public function __construct(
        SessionService $sessionService,
        SubjectService $subjectService,
        HeaderServices $headerService,
        NavigationServices $navigationServices,
        ValidationServices $validationService = new ValidationServices(),
    ) {
        $this->headerService     = $headerService;
        $this->navigationServices = $navigationServices;
        $this->validationService          = $validationService;
        $this->sessionService = $sessionService;
        $this->subjectService = $subjectService;
        $this->subjectModel       = new Subject();
        $this->redirectService     = new RedirectService();
    }

    public function create(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $this->handleFormSubmission();
            return;
        }

        $subjects         = $this->subjectService->getSubjects();
        $selected_subject = $this->subjectService->getSelectedSubject();
        $selected_page    = $this->subjectService->getSelectedPage();

        // We need to remove this line after refactoring.
        $pageModel = new Page();

        echo $this->headerService->getHeader('subject', $this->sessionService);

        // View for the new subject forms.
        include_once __DIR__ . '/../Views/templates/new_subject.php';
        include_once __DIR__ . '/../Views/partials/footer.php';
    }


    public function handleFormSubmission(): void
    {
        $menu_name = $_POST['menu_name'];
        $position  = $_POST['position'];
        $visible   = $_POST['visible'];

        $data = array(
            'menu_name' => $menu_name,
            'position' => $position,
            'visible' => $visible,
        );

        $errors          = [];
        $required_fields = ['menu_name', 'position', 'visible'];
        $errors          = $this->validationService->validateRequiredFields($required_fields, $data);

        $field_lengths = ['menu_name' => 30];
        $errors        = array_merge($errors, $this->validationService->validateMaxLengths($field_lengths));

        if (!empty($errors)) {
            $this->sessionService->set('errors', $errors);
            $this->redirectService->redirect('new_subject.php');
        }

        $new_id = $this->subjectModel->createNewSubject($menu_name, $position, $visible);

        if ($new_id) {
            $this->redirectService->redirect('content.php');
        } else {
            $this->sessionService->set('errors', ['Subject with this name already exists.']);
            $this->redirectService->redirect('new_subject.php');
        }
    }

    public function update(): void
    {
        $subjects        = $this->subjectService->getSubjects();
        $selectedSubject = $this->subjectService->getSelectedSubject();
        $subjParam       = $_GET['subj'] ?? null;
        $pageParam       = $_GET['page'] ?? null;

        if (intval($subjParam) === 0) {
            $this->redirectService->redirect('content.php');
        }

        // Handle form POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuName = $_POST['menu_name'];
            $position = (int) $_POST['position'];
            $visible  = (int) $_POST['visible'];

            $errors = array();

            $data = array(
                'menu_name' => $menuName,
                'position' => $position,
                'visible' => $visible,
            );

            // Required + length checks
            $errors = $this->validationService->validateRequiredFields(array('menu_name', 'position', 'visible' ), $data);
            $errors = array_merge(
                $errors,
                $this->validationService->validateMaxLengths(array('menu_name' => 30 ), $data)
            );

            error_log("Subj param " . $subjParam);
            error_log("Subj param " . $subjParam);
            error_log("Subj param " . $subjParam);

            if (! empty($errors)) {
                $this->sessionService->set('errors', $errors);
                $this->redirectService->redirect('edit_subject.php?subj=' . urlencode($subjParam));
            }

            $result = $this->subjectModel->updateSubject($subjParam, $menuName, $position, $visible);

            if ($result > 0) {
                $this->sessionService->set('message', 'The subject was successfully updated!');
            } else {
                $this->sessionService->set('message', 'No changes were made.');
            }

            $this->redirectService->redirect('edit_subject.php?subj=' . urlencode($subjParam));
        }

        $pageModel = new Page();

        // Render the template
        echo $this->headerService->getHeader('subject', $this->sessionService);

        $navigationHtml = $this->navigationServices->renderNavigation(
            $subjects,
            $subjParam,
            $pageParam,
            $pageModel
        );

        include __DIR__ . '/../Views/templates/edit_subject.php';
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
