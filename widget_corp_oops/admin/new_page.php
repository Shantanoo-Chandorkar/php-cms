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

$subjectId = intval($_GET['subj'] ?? 0);
if ($subjectId === 0) {
    $controller->redirect('content.php');
}

$controller->create($subjectId);

// Output header
echo $headerService->getHeader('edit_subject');
?>
<table id="structure" class="structure">
    <tr>
        <td id="navigation" class="navigation">
            <?php echo $navigationService->renderNavigation($subjects, $subj_param, $page_param, $db); ?>
        </td>
        <td id="page" class="page">
            <h2 class="form-title">Create New Page in: <?php echo $selected_subject['menu_name']; ?></h2>
            
            <?php require 'Views/partials/errors.php'; ?>
            <?php require 'Views/partials/messages.php'; ?>

            <form action="new_page.php?subj=<?php echo urlencode($selected_subject['id']); ?>" method="post" class="form-subject">
                <div class="form-group">
                    <label for="menu_name">Page Name:</label>
                    <input type="text" name="menu_name" id="menu_name" class="input-text" value="" />
                </div>
                
                <div class="form-group">
                    <label for="position">Position:</label>
                    <select name="position" id="position" class="input-select">
                        <?php
                            $page_count = $db->count_pages_for_subject($selected_subject['id']);
                        for ($count = 1; $count <= $page_count + 1; $count++) {
                            echo "<option value={$count}>{$count}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Visible:</label>
                    <label class="radio-option"><input type="radio" name="visible" value="0" checked /> No</label>
                    <label class="radio-option"><input type="radio" name="visible" value="1" /> Yes</label>
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
<?php require_once '../includes/footer.php'; ?>
<?php $db->close(); ?>
