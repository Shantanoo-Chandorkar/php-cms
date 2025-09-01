<?php 

require_once "helper/bootstrap.php"; 
require_once "includes/header.php"; 

echo get_header( "edit_subject" ); 
?>

<table id="structure" class="structure">
    <tr>
        <td id="navigation" class="navigation">
            <?php echo render_navigation_frontend($subjects, $subj_param, $page_param, $db); ?>
        </td>
        <td id="page" class="page">
            <?php
                if(!isset($selected_page) || !isset($selected_subject)) {  ?>
                    <h2 class="title">Welcome to Widget Corp!</h2>
            <?php } else {
            ?>
                <h2 class="title">
                    <?php 
                        if ($selected_page) {
                            $full_content = $selected_page['content'];
                            echo "<div>" . htmlspecialchars($selected_page['menu_name']) . "</div>";
                            echo "<br/>";
                            echo "<p class='page-content'>" . htmlspecialchars($full_content) . "</p>";
                            echo "<a class='back-to-home-link' href='index.php'>Back to home</a>";
                        } elseif ($selected_subject) {
                            echo htmlspecialchars($selected_subject['menu_name']);
                        } else {
                            echo "Select a subject or page to view";
                        }
                    ?>
                </h2>
            <?php }
            ?>
        </td>
    </tr>
</table>