<?php 
require_once "helper/bootstrap.php"; 
require_once "includes/header.php"; 

$db = new DBConnection( "widget_corp_test" );

$errors = array();

if ( intval( $_GET['subj'] ) === 0 ) {
    redirect_to( "content.php" );
}

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // Required fields
    $required_fields = ['menu_name', 'position', 'visible'];
    $errors = validate_required_fields( $required_fields );

    // Length restrictions
    $field_lengths = ['menu_name' => 30];
    $errors = array_merge( $errors, validate_max_lengths( $field_lengths ) );

    if ( !empty($errors) ) {
        // Store errors in session
        $_SESSION['errors'] = $errors;

        redirect_to( "edit_subject.php?subj=" . urlencode( $_GET['subj'] ) );
    }

    $menu_name = $_POST['menu_name'];
    $position = $_POST['position'];
    $visible = $_POST['visible'];

    $result = $db->update_subject( $_GET['subj'], $menu_name, $position, $visible );

    if ( $result > 0 ) {
        $_SESSION['message'] = "The subject was successfully updated!";
        redirect_to( "edit_subject.php?subj=" . urlencode( $_GET['subj'] ) );
    } else {
        $_SESSION['message'] = "No changes were made.";
        redirect_to( "edit_subject.php?subj=" . urlencode( $_GET['subj'] ) );
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
                <h2 class="form-title">Edit Subject: <?php echo $selected_subject['menu_name']; ?></h2>
                <?php if (!empty($_SESSION['errors'])): ?>
                    <div class="error-messages">
                        <ul>
                            <?php foreach ($_SESSION['errors'] as $error_field): ?>
                                <li>
                                    <?php
                                        // Customize messages per field
                                        switch ($error_field) {
                                            case 'menu_name':
                                                echo "Subject name is required and must be at most 30 characters.";
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
                <form action="edit_subject.php?subj=<?php echo urlencode( $selected_subject['id'] ); ?>" method="post" class="form-subject">
                    <div class="form-group">
                        <label for="menu_name">Subject Name:</label>
                        <input type="text" name="menu_name" id="menu_name" class="input-text"
                        value="<?php echo $selected_subject['menu_name']; ?>" />
                    </div>
                    
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <select name="position" id="position" class="input-select">
                            <?php 
                                $subject_count = count( $subjects );

                                for( $count = 1; $count <= $subject_count+1; $count++ ) {
                                    echo "<option value={$count}";
                                    if( $selected_subject['position'] === $count ) {
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
                                if( $selected_subject['visible'] === 0 ) {
                                    echo " checked";
                                } 
                            ?> /> No
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="visible" value="1" <?php
                                if( $selected_subject['visible'] === 1 ) {
                                    echo " checked";
                                } 
                            ?> /> Yes
                        </label>
                    </div>

                    <div class="form-actions">
                        <div class="button-container">
                            <input type="submit" name="submit" value="Edit Subject" class="btn btn-primary" />
                            <a href="delete_subject.php?subj=<?php echo urlencode( $selected_subject['id'] ); ?>" onclick="return confirm('Are you sure?');" class="btn btn-secondary">Delete Subject</a>
                        </div>
                        <div class="new-page-link-container">
                            <a href="new_page.php?subj=<?php echo urlencode( $selected_subject['id'] ); ?>">Add new page for this subject</a>
                        </div>
                    </div>
                </form>

                <br/>
                <a href="content.php" class="btn btn-secondary">Cancel</a>
            </td>
        </tr>
    </table>
<?php require_once("includes/footer.php"); ?>
<?php $db->close();?>

