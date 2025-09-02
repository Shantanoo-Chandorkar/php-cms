<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;
use Widget_Corps_Oops_Admin\Controllers\PageController;

$bootstrap         = new Bootstrap('widget_corp_test');
$headerService     = new HeaderServices();
$navigationService = new NavigationServices();
$subjects          = $bootstrap->getSubjects();
$selected_subject  = $bootstrap->getSelectedSubject();
$selected_page     = $bootstrap->getSelectedPage();
$subj_param        = $_GET['subj'] ?? null;
$page_param        = $_GET['page'] ?? null;
$db                = $bootstrap->getDB();
$controller        = new PageController($db);

$pageId = intval($_GET['page'] ?? 0);
if ($pageId === 0) {
    $controller->redirect('content.php');
}

$controller->update($pageId);

// Output header
echo $headerService->getHeader('edit_subject');
?>
<table id="structure" class="structure">
    <tr>
        <td id="navigation" class="navigation">
            <?php echo $navigationService->renderNavigation($subjects, $subj_param, $page_param, $db); ?>
        </td>
        <td id="page" class="page">
            <h2 class="form-title">Edit Page: <?php echo $selected_page['menu_name']; ?></h2>

            <?php require 'Views/partials/errors.php'; ?>
            <?php require 'Views/partials/messages.php'; ?>

            <form action="edit_page.php?page=<?php echo urlencode($selected_page['id']); ?>" method="post" class="form-subject">
                <div class="form-group">
                    <label for="menu_name">Page Name:</label>
                    <input type="text" name="menu_name" id="menu_name" class="input-text"
                        value="<?php echo $selected_page['menu_name']; ?>" />
                </div>
                
                <div class="form-group">
                    <label for="position">Position:</label>
                    <select name="position" id="position" class="input-select">
                        <?php
                            $page_count = $db->count_pages_for_subject($selected_page['subject_id']);
                        for ($count = 1; $count <= $page_count + 1; $count++) {
                            echo "<option value={$count}";
                            if ($selected_page['position'] === $count) {
                                echo ' selected';
                            }
                            echo ">{$count}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Visible:</label>
                    <label class="radio-option">
                        <input type="radio" name="visible" value="0" 
                        <?php
                        if ($selected_page['visible'] === 0) {
                            echo 'checked';
                        }
                        ?>
                                                                    /> No
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="visible" value="1" 
                        <?php
                        if ($selected_page['visible'] === 1) {
                            echo 'checked';
                        }
                        ?>
                                                                    /> Yes
                    </label>
                </div>

                <div class="form-group">
                    <label for="content">Page Content:</label>
                    <textarea name="content" id="content" class="input-text" rows="15"><?php echo htmlspecialchars($selected_page['content']); ?></textarea>
                </div>

                <div class="form-actions">
                    <input type="submit" name="submit" value="Edit Page" class="btn btn-primary" />
                    <a href="delete_page.php?page=<?php echo urlencode($selected_page['id']); ?>" onclick="return confirm('Are you sure?');" class="btn btn-secondary">Delete Page</a>
                </div>
            </form>

            <br/>
            <a href="content.php" class="btn btn-secondary">Cancel</a>
        </td>
    </tr>
</table>
<?php require_once '../includes/footer.php'; ?>
<?php $db->close(); ?>
