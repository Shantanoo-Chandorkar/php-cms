<?php
namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;

class NewSubjectController
{
    private Bootstrap $_bootstrap;
    private HeaderServices $_headerServices;
    private NavigationServices $_navigationServices;

    public function __construct(
        Bootstrap $bootstrap,
        HeaderServices $headerServices,
        NavigationServices $navigationServices
    ) {
        $this->_bootstrap = $bootstrap;
        $this->_headerServices = $headerServices;
        $this->_navigationServices = $navigationServices;
    }

    public function index(): void
    {
        $subjects = $this->_bootstrap->getSubjects();
        $selectedSubject = $this->_bootstrap->getSelectedSubject();
        $selectedPage = $this->_bootstrap->getSelectedPage();
        $db = $this->_bootstrap->getDB();

        echo $this->_headerServices->getHeader("edit_subject");
        ?>
        <table id="structure" class="structure">
            <tr>
                <td id="navigation" class="navigation">
                    <?php echo $this->_navigationServices->renderNavigation(
                        $subjects,
                        $selectedSubject['id'] ?? null,
                        $selectedPage['id'] ?? null,
                        $db
                    ); ?>
                </td>
                <td id="page" class="page">
                    <h2 class="form-title">Add Subject</h2>

                    <?php if (!empty($_SESSION['errors'])) : ?>
                        <div class="error-messages">
                            <ul>
                                <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form action="create_subject.php" method="post" class="form-subject">
                        <div class="form-group">
                            <label for="menu_name">Subject Name:</label>
                            <input type="text" name="menu_name" id="menu_name" class="input-text" />
                        </div>
                        
                        <div class="form-group">
                            <label for="position">Position:</label>
                            <select name="position" id="position" class="input-select">
                                <?php 
                                    $subject_count = count($subjects);
                                for ($count = 1; $count <= $subject_count + 1; $count++) {
                                    echo "<option value='{$count}'>{$count}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Visible:</label>
                            <label class="radio-option">
                                <input type="radio" name="visible" value="0" /> No
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="visible" value="1" /> Yes
                            </label>
                        </div>

                        <div class="form-actions">
                            <input type="submit" value="Add Subject" class="btn btn-primary" />
                            <a href="content.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </td>
            </tr>
        </table>
        <?php
        include_once __DIR__ . '/../../includes/footer.php';
        $db->close();
    }
}
