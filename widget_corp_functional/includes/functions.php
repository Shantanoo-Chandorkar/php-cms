<?php

require_once __DIR__ . "/../helper/bootstrap.php";

function render_navigation($subjects, $subj_param, $page_param, $db) {
    ob_start(); // capture HTML output
    ?>
    <ul class="subjects">
        <?php if (!empty($subjects)): ?>
            <?php foreach ($subjects as $subject_row): ?>
                <?php $active_subject = ($subj_param == $subject_row['id']) ? " active" : ""; ?>
                <li class="subject-item<?php echo $active_subject; ?>">
                    <a href="edit_subject.php?subj=<?php echo urlencode($subject_row['id']); ?>">
                        <?php echo htmlspecialchars($subject_row['menu_name']); ?>
                    </a>
                </li>
                <ul class="pages">
                    <?php 
                    $pages_for_subjects = $db->get_pages($subject_row['id']); 
                    if (!empty($pages_for_subjects)): 
                        foreach ($pages_for_subjects as $page_row): 
                            $active_page = ($page_param === $page_row['id']) ? " active" : ""; ?>
                            <li class="page-item<?php echo $active_page; ?>">
                                <a href="edit_page.php?page=<?php echo urlencode($page_row['id']); ?>">
                                    <?php echo htmlspecialchars($page_row['menu_name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    <?php
    return ob_get_clean(); // return the HTML
}

function render_navigation_frontend($subjects, $subj_param, $page_param, $db) {
    // If a page param is present, get that page once so we can detect its subject.
    $current_page = null;
    if (!empty($page_param)) {
        $page_id = (int)$page_param;
        $current_page = $db->get_page_by_id($page_id);
    }

    ob_start();
    ?>
    <ul class="subjects">
        <?php if (!empty($subjects)): ?>
            <?php foreach ($subjects as $subject_row):
                $subject_id = (int)$subject_row['id'];

                // Subject is active if either subj param matches OR the current page belongs to this subject
                $is_active_subject = ((int)$subj_param === $subject_id)
                                    || ($current_page && (int)$current_page['subject_id'] === $subject_id);

                $subject_active_class = $is_active_subject ? " active" : "";
                $pages_id = "pages-" . $subject_id;
            ?>
                <li class="subject-item<?php echo $subject_active_class; ?>">
                    <!-- button toggles the accordion locally (no navigation) -->
                    <button type="button" class="subject-toggle" data-target="<?php echo $pages_id; ?>" aria-expanded="<?php echo $is_active_subject ? 'true' : 'false'; ?>">
                        <?php echo htmlspecialchars($subject_row['menu_name']); ?>
                    </button>

                    <ul id="<?php echo $pages_id; ?>" class="pages<?php echo $is_active_subject ? " show" : ""; ?>">
                        <?php
                        $pages_for_subjects = $db->get_pages($subject_id);
                        if (!empty($pages_for_subjects)):
                            foreach ($pages_for_subjects as $page_row):
                                $page_row_id = (int)$page_row['id'];
                                $is_active_page = ($current_page && $page_row_id === (int)$current_page['id']) || ((int)$page_param === $page_row_id);
                                $page_active_class = $is_active_page ? " active" : "";
                        ?>
                                <li class="page-item<?php echo $page_active_class; ?>">
                                    <a href="<?php echo htmlspecialchars(BASE_URL . 'index.php?page=' . urlencode($page_row['id'])); ?>">
                                        <?php echo htmlspecialchars($page_row['menu_name']); ?>
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

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggles = document.querySelectorAll(".subject-toggle");

        toggles.forEach(toggle => {
            toggle.addEventListener("click", function() {
                const targetId = this.getAttribute("data-target");
                const targetList = document.getElementById(targetId);

                // collapse other open lists
                document.querySelectorAll(".pages.show").forEach(el => {
                    if (el !== targetList) {
                        el.classList.remove("show");
                        // update aria-expanded of the toggle that controls it
                        const btn = document.querySelector('[data-target="' + el.id + '"]');
                        if (btn) btn.setAttribute('aria-expanded', 'false');
                        // remove active class on subject item
                        if (btn && btn.parentElement) btn.parentElement.classList.remove('active');
                    }
                });

                // toggle current list
                const willShow = !targetList.classList.contains('show');
                targetList.classList.toggle("show", willShow);
                this.setAttribute('aria-expanded', willShow ? 'true' : 'false');

                // toggle active class on the subject <li>
                const subjectItem = this.parentElement;
                if (subjectItem) {
                    if (willShow) {
                        subjectItem.classList.add('active');
                    } else {
                        subjectItem.classList.remove('active');
                    }
                }
            });
        });
    });
    </script>

    <style>
    /* minimal styles for the accordion and active states */
    .pages { display: none; margin-left: 18px; padding-left: 0; }
    .pages.show { display: block; }
    .subject-toggle { background: none; border: none; padding: 0; cursor: pointer; color: inherit; text-align: left; }
    .subject-item.active > .subject-toggle { font-weight: 700; }
    .page-item.active a { font-weight: 700; color: inherit; }
    </style>

    <?php
    return ob_get_clean();
}

function render_users_navigation($users, $user_param) {
    ob_start(); ?>
    <ul class="users">
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user_row): ?>
                <?php $active_user = ($user_param == $user_row['id']) ? " active" : ""; ?>
                <li class="user-item<?php echo $active_user; ?>">
                    <a href="edit_user.php?user=<?php echo urlencode($user_row['id']); ?>">
                        <?php echo htmlspecialchars($user_row['username']); ?>
                        <span class="role">(<?php echo htmlspecialchars($user_row['role']); ?>)</span>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No staff users found.</li>
        <?php endif; ?>
    </ul>
    <?php
    return ob_get_clean();
}

function redirect_to( $location = null ) {
    if( $location !== null ) {
        header( "Location: {$location}" );
        exit;
    }
}
 
/**
 * Validate required POST fields.
 *
 * @param array $required_fields List of field names that must be present and non-empty.
 * @return array List of missing field names (empty if all valid).
 */
function validate_required_fields( array $required_fields ): array {
    $errors = [];
    foreach ( $required_fields as $field ) {
        if ( !isset( $_POST[$field] ) || trim( $_POST[$field] ) === '') {
            $errors[] = $field;
        }
    }
    return $errors;
}

/**
 * Validate max length for given fields.
 *
 * @param array $field_lengths Associative array like ['menu_name' => 30]
 * @return array List of fields that failed length validation
 */
function validate_max_lengths( array $field_lengths ): array {
    $errors = [];
    foreach ( $field_lengths as $field => $max_length ) {
        if ( isset( $_POST[$field] ) && strlen( trim( $_POST[$field] ) ) > $max_length ) {
            $errors[] = $field;
        }
    }
    return $errors;
}