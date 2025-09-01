<?php 
require_once "helper/bootstrap.php"; 
require_once "includes/header.php"; 

$db = new DBConnection( "widget_corp_test" );

$errors = array();

if ( intval( $_GET['page'] ) === 0 ) {
    redirect_to( "content.php" );
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

        redirect_to( "edit_page.php?page=" . urlencode( $_GET['page'] ) );
    }

    $menu_name = $_POST['menu_name'];
    $position = $_POST['position'];
    $visible = $_POST['visible'];
    $content = $_POST['content'];

    $result = $db->update_page( $_GET['page'], $menu_name, $position, $visible, $content );

    if ( $result > 0 ) {
        $_SESSION['message'] = "The page was successfully updated!";
        redirect_to( "edit_page.php?page=" . urlencode( $_GET['page'] ) );
    } else {
        $_SESSION['message'] = "No changes were made.";
        redirect_to( "edit_page.php?page=" . urlencode( $_GET['page'] ) );
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
                <h2 class="form-title">Edit Page: <?php echo $selected_page['menu_name']; ?></h2>
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
                <form action="edit_page.php?page=<?php echo urlencode( $selected_page['id'] ); ?>" method="post" class="form-subject">
                    <div class="form-group">
                        <label for="menu_name">Page Name:</label>
                        <input type="text" name="menu_name" id="menu_name" class="input-text"
                        value="<?php echo $selected_page['menu_name']; ?>" />
                    </div>
                    
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <select name="position" id="position" class="input-select">
                            <?php 
                                $page_count = $db->count_pages_for_subject( $selected_page['subject_id'] ); 
                                for( $count = 1; $count <= $page_count+1; $count++ ) {
                                    echo "<option value={$count}";
                                    if( $selected_page['position'] === $count ) {
                                        echo " selected";
                                    }
                                    echo ">{$count}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Visible:</label>
                        <label class="radio-option">
                            <input type="radio" name="visible" value="0" <?php
                                if( $selected_page['visible'] === 0 ) {
                                    echo " checked";
                                } 
                            ?> /> No
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="visible" value="1" <?php
                                if( $selected_page['visible'] === 1 ) {
                                    echo " checked";
                                } 
                            ?> /> Yes
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="content">Page Content:</label>
                            <textarea name="content" id="content" class="input-text" rows="15"><?php echo htmlspecialchars($selected_page['content']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <input type="submit" name="submit" value="Edit Page" class="btn btn-primary" />
                        <a href="delete_page.php?page=<?php echo urlencode( $selected_page['id'] ); ?>" onclick="return confirm('Are you sure?');" class="btn btn-secondary">Delete Page</a>
                    </div>
                </form>

                <br/>
                <a href="content.php" class="btn btn-secondary">Cancel</a>
            </td>
        </tr>
    </table>
<?php require_once("includes/footer.php"); ?>
<?php $db->close();?>

