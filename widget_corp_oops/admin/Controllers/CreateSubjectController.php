<?php
namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Services\ValidationServices;

class CreateSubjectController {

    private Bootstrap $_bootstrap;
    private ValidationServices $_validation;

    public function __construct(
        Bootstrap $bootstrap,
        ValidationServices $validation
    ) {
        $this->_bootstrap  = $bootstrap;
        $this->_validation = $validation;
    }

    public function index(): void {
        $db = $this->_bootstrap->getDB();

        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            $this->redirect( 'new_subject.php' );
        }

        $errors          = array();
        $required_fields = array( 'menu_name', 'position', 'visible' );
        $errors          = $this->_validation->validateRequiredFields( $required_fields );

        $field_lengths = array( 'menu_name' => 30 );
        $errors        = array_merge( $errors, $this->_validation->validateMaxLengths( $field_lengths ) );

        if ( ! empty( $errors ) ) {
            $_SESSION['errors'] = $errors;
            $this->redirect( 'new_subject.php' );
        }

        $menu_name = $_POST['menu_name'];
        $position  = $_POST['position'];
        $visible   = $_POST['visible'];

        $new_id = $db->create_new_subject( $menu_name, $position, $visible );

        if ( $new_id ) {
            $this->redirect( 'content.php' );
        } else {
            $_SESSION['errors'] = array( 'Subject with this name already exists.' );
            $this->redirect( 'new_subject.php' );
        }

        $db->close();
    }

    private function redirect( string $url ): void {
        header( "Location: $url" );
        exit;
    }
}
