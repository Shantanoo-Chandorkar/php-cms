<?php
namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;

class DeletePageController {

    private Bootstrap $_bootstrap;

    public function __construct( Bootstrap $bootstrap ) {
        $this->_bootstrap = $bootstrap;
    }

    public function index(): void {
        $db     = $this->_bootstrap->getDB();
        $pageId = isset( $_GET['page'] ) ? (int) $_GET['page'] : 0;

        if ( $pageId === 0 ) {
            $this->redirect( 'content.php' );
        }

        $result = $db->delete_page_by_id( $pageId );

        if ( $result > 0 ) {
            $this->redirect( 'content.php' );
        } else {
            echo '<p>Failed to delete page.</p>';
            echo "<a href='content.php'>Return to the Main Page</a>";
        }
    }

    private function redirect( string $url ): void {
        header( "Location: $url" );
        exit;
    }
}
