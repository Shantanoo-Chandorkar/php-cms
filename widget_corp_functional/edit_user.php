<?php 
require_once "helper/bootstrap.php"; 
require_once "includes/header.php"; 

$db = new DBConnection("widget_corp_test");

$errors = array();

if (intval($_GET['user']) === 0) {
    redirect_to("content.php");
}

$user_id = $_GET['user'];
$selected_user = $db->get_user_by_id($user_id);

if (!$selected_user) {
    $_SESSION['message'] = "User not found.";
    redirect_to("content.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['username', 'role'];
    $errors = validate_required_fields($required_fields);

    $field_lengths = ['username' => 50];
    $errors = array_merge($errors, validate_max_lengths($field_lengths));

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        redirect_to("edit_user.php?user=" . urlencode($user_id));
    }

    $username = trim($_POST['username']);
    $role     = $_POST['role'];
    $password = trim($_POST['password']);

    if (!in_array($role, ['admin', 'subscriber'])) {
        $role = 'subscriber';
    }

    // Update password only if provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $hashed_password = $selected_user['password']; 
    }

    $success = $db->update_user($user_id, $username, $hashed_password, $role);

    if ($success) {
        $_SESSION['message'] = "User updated successfully!";
        redirect_to("edit_user.php?user=" . urlencode($user_id));
    } else {
        $_SESSION['message'] = "No changes were made.";
        redirect_to("edit_user.php?user=" . urlencode($user_id));
    }
}

echo get_header("edit_subject"); 
?>
    <table id="structure" class="structure">
        <tr>
            <td id="navigation" class="navigation">
                <?php echo render_navigation($subjects, $subj_param, $page_param, $db); ?>
            </td>
            <td id="page" class="page">
                <h2 class="form-title">Edit User: <?php echo htmlspecialchars($selected_user['username']); ?></h2>

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

                <form action="edit_user.php?user=<?php echo urlencode($user_id); ?>" method="post" class="form-subject">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" class="input-text" 
                               value="<?php echo htmlspecialchars($selected_user['username']); ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="password">Password (leave blank to keep unchanged):</label>
                        <input type="password" name="password" id="password" class="input-text" />
                    </div>

                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select name="role" id="role" class="input-select">
                            <option value="subscriber" <?php echo $selected_user['role'] === 'subscriber' ? 'selected' : ''; ?>>Subscriber</option>
                            <option value="admin" <?php echo $selected_user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <input type="submit" name="submit" value="Update User" class="btn btn-primary" />
                        <a href="delete_user.php?user=<?php echo urlencode( $user_id ); ?>" onclick="return confirm('Are you sure?');" class="btn btn-secondary">Delete User</a>
                    </div>
                </form>

                <br/>
                <a href="content.php" class="btn btn-secondary">Cancel</a>
            </td>
        </tr>
    </table>
<?php require_once("includes/footer.php"); ?>
<?php $db->close(); ?>
