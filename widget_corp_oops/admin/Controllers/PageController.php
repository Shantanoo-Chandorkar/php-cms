<?php
namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\DBConnection;
use Widget_Corps_Oops_Admin\Services\ValidationServices;

class PageController {

    private DBConnection $_db;
    private ValidationServices $_validation;

    public function __construct(
        DBConnection $db,
        ValidationServices $validation = new ValidationServices()
    ) {
        $this->_db         = $db;
        $this->_validation = $validation;
    }

    public function create( int $subjectId ): void {
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return;
        }

        $errors          = array();
        $required_fields = array( 'menu_name', 'position', 'visible' );
        $errors          = $this->_validation->validateRequiredFields( $required_fields );

        $field_lengths = array( 'menu_name' => 30 );
        $errors        = array_merge( $errors, $this->_validation->validateMaxLengths( $field_lengths ) );

        if ( ! empty( $errors ) ) {
            $_SESSION['errors'] = $errors;
            $this->redirect( 'new_page.php?subj=' . urlencode( $subjectId ) );
        }

        $menuName = $_POST['menu_name'];
        $position = $_POST['position'];
        $visible  = $_POST['visible'];
        $content  = $_POST['content'];

        $newPageId = $this->_db->create_new_page( $subjectId, $menuName, $position, $visible, $content );

        if ( $newPageId ) {
            $_SESSION['message'] = 'The page was successfully created!';
            $this->redirect( 'edit_page.php?page=' . urlencode( $newPageId ) );
        } else {
            $_SESSION['message'] = 'Page could not be created.';
            $this->redirect( 'new_page.php?subj=' . urlencode( $subjectId ) );
        }
    }

    public function update( int $pageId ): void {
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return;
        }

        $errors          = array();
        $required_fields = array( 'menu_name', 'position', 'visible' );
        $errors          = $this->_validation->validateRequiredFields( $required_fields );

        $field_lengths = array( 'menu_name' => 30 );
        $errors        = array_merge( $errors, $this->_validation->validateMaxLengths( $field_lengths ) );

        if ( ! empty( $errors ) ) {
            $_SESSION['errors'] = $errors;
            $this->redirect( 'edit_page.php?page=' . urlencode( $pageId ) );
        }

        $menuName = $_POST['menu_name'];
        $position = $_POST['position'];
        $visible  = $_POST['visible'];
        $content  = $_POST['content'];

        $result = $this->_db->update_page( $pageId, $menuName, $position, $visible, $content );

        if ( $result > 0 ) {
            $_SESSION['message'] = 'The page was successfully updated!';
        } else {
            $_SESSION['message'] = 'No changes were made.';
        }
        $this->redirect( 'edit_page.php?page=' . urlencode( $pageId ) );
    }

    public function redirect( string $url ): void {
        header( "Location: $url" );
        exit;
    }
}
