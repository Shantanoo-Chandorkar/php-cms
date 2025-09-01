<?php 
require_once("helper/bootstrap.php"); 
require_once("includes/header.php"); 

$db = new DBConnection("widget_corp_test");

// Fetch all users
$users = $db->get_all_users();
$user_param = isset($_GET['id']) ? intval($_GET['id']) : null;

// output the header with a chosen stylesheet
echo get_header("staff"); 
?>
    <table id="structure" class="structure">
        <tr>
            <td id="navigation" class="navigation">
                <?php echo render_users_navigation($users, $user_param); ?>
            </td>
            <td id="page" class="page">
                <h2 class="title">Staff Menu</h2>
                <p class="sub-title">Welcome to the staff menu</p>
                <ul class="menu">
                    <li><a href="content.php">Manage Website Content</a></li>
                    <li><a href="new_user.php">Add Staff User</a></li>
                    <li><a href="auth/logout.php">Logout</a></li>
                </ul>
            </td>
        </tr>
    </table>
<?php include("includes/footer.php"); ?>
<?php $db->close(); ?>
