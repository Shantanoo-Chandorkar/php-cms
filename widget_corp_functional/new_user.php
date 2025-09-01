<?php 
require_once "helper/bootstrap.php"; 
require_once "includes/header.php"; 

$db = new DBConnection("widget_corp_test");

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Required fields
    $required_fields = ['username', 'password', 'role'];
    $errors = validate_required_fields($required_fields);

    // Length restrictions
    $field_lengths = ['username' => 50, 'password' => 255];
    $errors = array_merge($errors, validate_max_lengths($field_lengths));

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        redirect_to("new_user.php");
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    // Only allow valid roles
    if (!in_array($role, ['admin', 'subscriber'])) {
        $role = 'subscriber';
    }

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $new_user_id = $db->create_new_user($username, $hashed_password, $role);

    if ($new_user_id) {
        $_SESSION['message'] = "The user was successfully created!";
        redirect_to("staff.php");
    } else {
        $_SESSION['message'] = "User already exist.";
        redirect_to("new_user.php");
    }
}

// output the header with a chosen stylesheet
echo get_header("edit_subject"); 
?>
    <table id="structure" class="structure">
        <tr>
            <td id="navigation" class="navigation">
                <?php echo render_navigation($subjects, $subj_param, $page_param, $db); ?>
            </td>
            <td id="page" class="page">
                <h2 class="form-title">Create New User</h2>

                <?php if (!empty($_SESSION['errors'])): ?>
                    <div class="error-messages">
                        <ul>
                            <?php foreach ($_SESSION['errors'] as $error_field): ?>
                                <li>
                                    <?php
                                        switch ($error_field) {
                                            case 'username':
                                                echo "Username is required and must be at most 50 characters.";
                                                break;
                                            case 'password':
                                                echo "Password is required.";
                                                break;
                                            case 'role':
                                                echo "Role is required.";
                                                break;
                                            default:
                                                echo "Unknown error with $error_field.";
                                        }
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <?php
                if (!empty($_SESSION['message'])) {
                    echo "<div class='flash-message'>" . $_SESSION['message'] . "</div>";
                    unset($_SESSION['message']);
                }
                ?>

                <form action="new_user.php" method="post" class="form-subject">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" class="input-text" value="" required />
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" class="input-text" required />
                    </div>

                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select name="role" id="role" class="input-select">
                            <option value="subscriber">Subscriber</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <input type="submit" name="submit" value="Create User" class="btn btn-primary" />
                    </div>
                </form>

                <br/>
                <a href="content.php" class="btn btn-secondary">Cancel</a>
            </td>
        </tr>
    </table>
<?php require_once("includes/footer.php"); ?>
<?php $db->close(); ?>
