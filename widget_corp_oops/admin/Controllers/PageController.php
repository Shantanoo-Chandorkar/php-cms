<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Admin\Services\ValidationServices;
use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\RedirectService;
use Widget_Corp_Oops_Admin\Services\SessionService;
use Widget_Corp_Oops_Admin\Services\SubjectService;
use Widget_Corp_Oops_Admin\Models\Page;

class PageController
{
    private HeaderServices $headerServices;
    private NavigationServices $navigationServices;
    private RedirectService $redirectService;
    private Page $pageModel;
    private ValidationServices $validationServices;
    private SessionService $sessionService;
    private SubjectService $subjectService;

    public function __construct(
        SessionService $sessionService,
        SubjectService $subjectService,
        HeaderServices $headerServices,
        NavigationServices $navigationServices,
        ValidationServices $validation = new ValidationServices()
    ) {
        $this->headerServices     = $headerServices;
        $this->navigationServices = $navigationServices;
        $this->validationServices = $validation;
        $this->sessionService = $sessionService;
        $this->subjectService = $subjectService;
        $this->pageModel         = new Page();
        $this->redirectService     = new RedirectService();
    }

    public function create(int $subjectId): void
    {
        if (0 === $subjectId) {
            $this->redirectService->redirect('content.php');
        }

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $this->handleFormSubmission($subjectId);
            return;
        }

        // Data for View.
        $subjects = $this->subjectService->getSubjects();
        $selected_subject = $this->subjectService->getSelectedSubject();
        $selected_page = $this->subjectService->getSelectedPage();

        // Create page model for navigation service
        echo $this->headerServices->getHeader('edit_subject');

        include_once __DIR__ . '/../Views/templates/new_page.php';
        include_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function handleFormSubmission(int $subjectId): void
    {
        $errors          = [];
        $required_fields = ['menu_name', 'position', 'visible'];
        $errors          = $this->validationServices->validateRequiredFields($required_fields);

        $field_lengths = ['menu_name' => 30];
        $errors        = array_merge($errors, $this->validationServices->validateMaxLengths($field_lengths));

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirectService->redirect('new_page.php?subj=' . urlencode($subjectId));
        }

        $menuName = $_POST['menu_name'];
        $position = $_POST['position'];
        $visible  = $_POST['visible'];
        $content  = $_POST['content'];

        $newPageId = $this->pageModel->createNewPage($subjectId, $menuName, $position, $visible, $content);

        if ($newPageId) {
            $_SESSION['message'] = 'The page was successfully created!';
            $this->redirectService->redirect('edit_page.php?page=' . urlencode($newPageId));
        } else {
            $_SESSION['message'] = 'Page could not be created.';
            $this->redirectService->redirect('new_page.php?subj=' . urlencode($subjectId));
        }
    }

    public function update(int $pageId): void
    {
        if ($pageId === 0) {
            $this->redirectService->redirect('content.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors          = [];
            $required_fields = ['menu_name', 'position', 'visible'];
            $errors          = $this->validationServices->validateRequiredFields($required_fields);

            $field_lengths   = ['menu_name' => 30];
            $errors          = array_merge($errors, $this->validationServices->validateMaxLengths($field_lengths));

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $this->redirectService->redirect('edit_page.php?page=' . urlencode($pageId));
            }

            $menuName = $_POST['menu_name'];
            $position = (int) $_POST['position'];
            $visible  = (bool) $_POST['visible'];
            $content  = $_POST['content'];

            $result = $this->pageModel->updatePageById($pageId, $menuName, $position, $visible, $content);

            if ($result) {
                $_SESSION['message'] = 'The page was successfully updated!';
            } else {
                $_SESSION['message'] = 'No changes were made.';
            }

            $this->redirectService->redirect('edit_page.php?page=' . urlencode($pageId));
            return;
        }

        // GET request – load the form with existing data
        $subjects          = $this->subjectService->getSubjects();
        $selected_subject  = $this->subjectService->getSelectedSubject();
        $selected_page     = $this->subjectService->getSelectedPage();

        echo $this->headerServices->getHeader('edit_subject');

        include_once __DIR__ . '/../Views/templates/edit_page.php';
        include_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function delete(): void
    {
        $pageId = isset($_GET['page']) ? (int) $_GET['page'] : 0;

        if ($pageId === 0) {
            $this->redirectService->redirect('content.php');
        }

        $result = $this->pageModel->deletePageById($pageId);

        if ($result > 0) {
            $this->redirectService->redirect('content.php');
        } else {
            echo '<p>Failed to delete page.</p>';
            echo "<a href='content.php'>Return to the Main Page</a>";
        }
    }
}
