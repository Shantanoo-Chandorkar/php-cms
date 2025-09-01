<?php
namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;

class ContentController {

    private Bootstrap $_bootstrap;
    private HeaderServices $_headerService;
    private NavigationServices $_navigationService;

    public function __construct(
        Bootstrap $bootstrap,
        HeaderServices $headerService,
        NavigationServices $navigationService
    ) {
        $this->_bootstrap         = $bootstrap;
        $this->_headerService     = $headerService;
        $this->_navigationService = $navigationService;
    }

    public function index(): void {
        $subjects        = $this->_bootstrap->getSubjects();
        $selectedSubject = $this->_bootstrap->getSelectedSubject();
        $selectedPage    = $this->_bootstrap->getSelectedPage();
        $subjParam       = $_GET['subj'] ?? null;
        $pageParam       = $_GET['page'] ?? null;
        $db              = $this->_bootstrap->getDB();

        // Render header
        echo $this->_headerService->getHeader( 'content' );
        ?>
        <table id="structure" class="structure">
            <tr>
                <td id="navigation" class="navigation">
                    <?php
                    echo $this->_navigationService->renderNavigation(
                        $subjects,
                        $subjParam,
                        $pageParam,
                        $db
                    );
                    ?>
                    <br/>
                    <a href="new_subject.php">+ Add a new subject</a>
                </td>
                <td id="page" class="page">
                    <h2 class="title">
                        <?php
                        if ( $selectedSubject ) {
                            echo htmlspecialchars( $selectedSubject['menu_name'] );
                        } elseif ( $selectedPage ) {
                            $fullContent = $selectedPage['content'];
                            $limit       = 100;

                            if ( strlen( $fullContent ) > $limit ) {
                                $excerpt = substr( $fullContent, 0, $limit ) . '...';
                            } else {
                                $excerpt = $fullContent;
                            }

                            echo '<div>' . htmlspecialchars( $selectedPage['menu_name'] ) . '</div>';
                            echo '<br/>';
                            echo "<p class='page-excerpt'>" . htmlspecialchars( $excerpt ) . '</p>';
                            echo "<a href='edit_page.php?page={$selectedPage['id']}'>Edit Page</a>";
                        } else {
                            echo 'Select a subject or page to edit';
                        }
                        ?>
                    </h2>
                </td>
            </tr>
        </table>
        <?php
        include_once __DIR__ . '/../../includes/footer.php';
    }
}
