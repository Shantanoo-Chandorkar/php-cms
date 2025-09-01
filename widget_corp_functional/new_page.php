<?php 
require_once "helper/bootstrap.php"; 
require_once "includes/header.php"; 

$db = new DBConnection( "widget_corp_test" );

$errors = array();

if ( intval($_GET['subj']) === 0 ) {
    redirect_to("content.php");
}

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // Required fields
    $required_fields = ['menu_name', 'position', 'visible', 'content'];
    $errors = validate_required_fields( $required_fields );

    // Length restrictions
    $field_lengths = ['menu_name' => 30];
    $errors = array_merge( $errors, validate_max_lengths( $field_lengths ) );

    if ( !empty($errors) ) {
        // Store errors in session
        $_SESSION['errors'] = $errors;

        redirect_to( "new_page.php?subj=" . urlencode( $_GET['subj'] ) );
    }

    $menu_name = $_POST['menu_name'];
    $position = $_POST['position'];
    $visible = $_POST['visible'];
    $content = $_POST['content'];
    $subject_id = $_GET['subj'];

    $new_page_id = $db->create_new_page( $subject_id, $menu_name, $position, $visible, $content );

    if ( $new_page_id ) {
        $_SESSION['message'] = "The page was successfully updated!";
        redirect_to( "edit_page.php?page=" . urlencode( $new_page_id ) );
    } else {
        $_SESSION['message'] = "No changes were made.";
        redirect_to( "new_page.php?subj=" . urlencode( $subject_id ) );
    }
}

// output the header with a chosen stylesheet
echo get_header( "edit_subject" ); 
?>
    <table id="structure" class="structure">
        <tr>
            <td id="navigation" class="navigation">
                <?php echo render_navigation($subjects, $subj_param, $page_param, $db); ?>
            </td>
            <td id="page" class="page">
                <h2 class="form-title">Create New Page in: <?php echo $selected_subject['menu_name']; ?></h2>
                <?php if (!empty($_SESSION['errors'])): ?>
                    <div class="error-messages">
                        <ul>
                            <?php foreach ($_SESSION['errors'] as $error_field): ?>
                                <li>
                                    <?php
                                        // Customize messages per field
                                        switch ($error_field) {
                                            case 'menu_name':
                                                echo "Page name is required and must be at most 30 characters.";
                                                break;
                                            case 'position':
                                                echo "Position is required.";
                                                break;
                                            case 'visible':
                                                echo "Visible is required.";
                                                break;
                                            default:
                                                echo "Unknown error with $error_field.";
                                        }
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['errors']); // Clear errors ?>
                <?php endif; ?>

                <?php
                if ( !empty( $_SESSION['message'] ) ) {
                    echo "<div class='flash-message'>" . $_SESSION['message'] . "</div>";
                    unset( $_SESSION['message'] ); // clear after showing
                }
                ?>
                <form action="new_page.php?subj=<?php echo urlencode($selected_subject['id']); ?>" method="post" class="form-subject">
                    <div class="form-group">
                        <label for="menu_name">Page Name:</label>
                        <input type="text" name="menu_name" id="menu_name" class="input-text"
                            value="" />
                    </div>
                    
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <select name="position" id="position" class="input-select">
                            <?php 
                                $page_count = $db->count_pages_for_subject($selected_subject['id']); 
                                for ($count = 1; $count <= $page_count+1; $count++) {
                                    echo "<option value={$count}>{$count}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Visible:</label>
                        <label class="radio-option">
                            <input type="radio" name="visible" value="0" checked /> No
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="visible" value="1" /> Yes
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="content">Page Content:</label>
                        <textarea name="content" id="content" class="input-text" rows="15"></textarea>
                    </div>

                    <div class="form-actions">
                        <input type="submit" name="submit" value="Create Page" class="btn btn-primary" />
                    </div>
                </form>

                <br/>
                <a href="content.php" class="btn btn-secondary">Cancel</a>
            </td>
        </tr>
    </table>
<?php require_once("includes/footer.php"); ?>
<?php $db->close();?>

