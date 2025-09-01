<?php
namespace Widget_Corps_Oops_Admin\Services;

use Widget_Corps_Oops_Helper\DBConnection;

class NavigationServices {

    public function renderNavigation( array $subjects, ?int $subjParam, ?int $pageParam, DBConnection $db ): string {
        ob_start(); ?>
        <ul class="subjects">
            <?php foreach ( $subjects as $subject ) : ?>
                <?php $activeSubject = ( $subjParam == $subject['id'] ) ? ' active' : ''; ?>
                <li class="subject-item<?php echo $activeSubject; ?>">
                    <a href="edit_subject.php?subj=<?php echo urlencode( (string) $subject['id'] ); ?>">
                        <?php echo htmlspecialchars( $subject['menu_name'] ); ?>
                    </a>
                    <?php
                    $pages = $db->get_pages( $subject['id'] );
                    if ( $pages ) :
                        ?>
                        <ul class="pages">
                            <?php foreach ( $pages as $page ) : ?>
                                <?php $activePage = ( $pageParam == $page['id'] ) ? ' active' : ''; ?>
                                <li class="page-item<?php echo $activePage; ?>">
                                    <a href="edit_page.php?page=<?php echo urlencode( (string) $page['id'] ); ?>">
                                        <?php echo htmlspecialchars( $page['menu_name'] ); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
        return ob_get_clean();
    }

    public function renderUsersNavigation( array $users, ?int $activeUserId ): string {
        ob_start();
        ?>
            <ul class="users">
                    <?php if ( ! empty( $users ) ) : ?>
                        <?php foreach ( $users as $user ) : ?>
                            <?php $active = ( $activeUserId === $user->id ) ? ' active' : ''; ?>
                        <li class="user-item<?php echo $active; ?>">
                            <a href="edit_user.php?user=<?php echo urlencode( (string) $user->id ); ?>">
                                <?php echo htmlspecialchars( $user->username ); ?>
                                <span class="role">(<?php echo htmlspecialchars( $user->role ); ?>)</span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                <?php else : ?>
                    <li>No staff users found.</li>
                <?php endif; ?>
            </ul>
        <?php
        return ob_get_clean();
    }

    public function renderFrontendNavigation( array $subjects, ?int $subjParam, ?int $pageParam, DBConnection $db ): string {
        $currentPage = null;
        if ( ! empty( $pageParam ) ) {
            $pageId      = (int) $pageParam;
            $currentPage = $db->get_page_by_id( $pageId );
        }

        ob_start();
        ?>
        <ul class="subjects">
            <?php if ( ! empty( $subjects ) ) : ?>
                <?php
                foreach ( $subjects as $subjectRow ) :
                    $subjectId = (int) $subjectRow['id'];

                    // Subject active if subj matches OR the current page belongs to this subject.
                    $isActiveSubject = ( $subjParam === $subjectId )
                    || ( $currentPage && (int) $currentPage['subject_id'] === $subjectId );

                    $subjectActiveClass = $isActiveSubject ? ' active' : '';
                    $pagesId            = 'pages-' . $subjectId;
                    ?>
                    <li class="subject-item<?php echo $subjectActiveClass; ?>">
                        <button type="button"
                                class="subject-toggle"
                                data-target="<?php echo $pagesId; ?>"
                                aria-expanded="<?php echo $isActiveSubject ? 'true' : 'false'; ?>">
                            <?php echo htmlspecialchars( $subjectRow['menu_name'] ); ?>
                        </button>

                        <ul id="<?php echo $pagesId; ?>" class="pages<?php echo $isActiveSubject ? ' show' : ''; ?>">
                            <?php
                            $pagesForSubjects = $db->get_pages( $subjectId );
                            if ( ! empty( $pagesForSubjects ) ) :
                                foreach ( $pagesForSubjects as $pageRow ) :
                                    $pageRowId       = (int) $pageRow['id'];
                                    $isActivePage    = ( $currentPage && $pageRowId === (int) $currentPage['id'] )
                                                    || ( $pageParam === $pageRowId );
                                    $pageActiveClass = $isActivePage ? ' active' : '';
                                    ?>
                                    <li class="page-item<?php echo $pageActiveClass; ?>">
                                        <a href="<?php echo htmlspecialchars( BASE_URL . 'index.php?page=' . urlencode( $pageRow['id'] ) ); ?>">
                                            <?php echo htmlspecialchars( $pageRow['menu_name'] ); ?>
                                        </a>
                                    </li>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <?php
        return ob_get_clean();
    }

    function getSiteURL(): string {
        $protocol   = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) ? 'https' : 'http';
        $host       = $_SERVER['HTTP_HOST']; // e.g., localhost or your domain
        $scriptPath = dirname( $_SERVER['SCRIPT_NAME'] ); // e.g., /widget_corp/widget_corp_oops
        $scriptPath = rtrim( $scriptPath, '/\\' ); // remove trailing slash

        return $protocol . '://' . $host . $scriptPath . '/';
    }
}
