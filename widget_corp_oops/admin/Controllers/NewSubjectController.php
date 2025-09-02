<?php

namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;
use Widget_Corps_Oops_Admin\Services\ValidationServices;
use Widget_Corps_Oops_Admin\Models\Subject;

class NewSubjectController
{
    private Bootstrap $bootstrap;
    private HeaderServices $header_services;
    private NavigationServices $navigation_services;
    private ValidationServices $validation;
    private Subject $subject_model;

    public function __construct(
        Bootstrap $bootstrap,
        HeaderServices $header_services,
        NavigationServices $navigation_services,
        ValidationServices $validation = new ValidationServices()
    ) {
        $this->bootstrap           = $bootstrap;
        $this->header_services     = $header_services;
        $this->navigation_services = $navigation_services;
        $this->validation          = $validation;
        $this->subject_model       = new Subject();
    }

    public function index(): void
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

        echo $this->header_services->getHeader('edit_subject');

        // View for the new subject forms.
        include __DIR__ . '/../Views/new_subject.php';
        include_once __DIR__ . '/../../includes/footer.php';
    }


    public function handleFormSubmission(): void
    {
        $errors          = [];
        $required_fields = ['menu_name', 'position', 'visible'];
        $errors          = $this->validation->validateRequiredFields($required_fields);

        $field_lengths = ['menu_name' => 30];
        $errors        = array_merge($errors, $this->validation->validateMaxLengths($field_lengths));

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('new_subject.php');
        }

        $menu_name = $_POST['menu_name'];
        $position  = $_POST['position'];
        $visible   = $_POST['visible'];

        $new_id = $this->subject_model->createNewSubject($menu_name, $position, $visible);

        if ($new_id) {
            $this->redirect('content.php');
        } else {
            $_SESSION['errors'] = ['Subject with this name already exists.'];
            $this->redirect('new_subject.php');
        }
    }

    private function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
