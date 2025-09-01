<?php 
require_once "helper/bootstrap.php";
require_once "includes/header.php";

// output the header with a chosen stylesheet
echo get_header("content"); 
?>
    <table id="structure" class="structure">
        <tr>
            <td id="navigation" class="navigation">
                <?php echo render_navigation($subjects, $subj_param, $page_param, $db); ?>
                <br/>
                <a href="new_subject.php">+ Add a new subject</a>
            </td>
            <td id="page" class="page">
                <h2 class="title">
                    <?php 
                    if ( isset( $selected_subject ) ) {
                        echo htmlspecialchars( $selected_subject['menu_name'] );
                    } elseif ( isset( $selected_page ) ) {
                        $full_content = $selected_page['content'];
                        $limit = 100;

                        if( strlen( $full_content ) > $limit ) {
                            $excerpt = substr( $full_content, 0, $limit ) . "...";
                        } else {
                            $excerpt = $full_content;
                        }

                        echo "<div>" . htmlspecialchars( $selected_page['menu_name'] ) . "</div>";
                        echo "<br/>";
                        echo "<p class='page-excerpt'>" . htmlspecialchars( $excerpt ) ."</p>";
                        echo "<a href='edit_page.php?page={$selected_page['id']}'>Edit Page</a>";
                    } else {
                        echo "Select a subject or page to edit";
                    }
                    ?>
                </h2>
            </td>
        </tr>
    </table>
<?php require_once("includes/footer.php"); ?>
<?php $db->close();?>

