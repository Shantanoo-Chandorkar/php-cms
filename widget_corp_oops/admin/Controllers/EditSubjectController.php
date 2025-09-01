<?php
namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Helper\Bootstrap;
use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;
use Widget_Corps_Oops_Admin\Services\ValidationServices;

class EditSubjectController
{
    private Bootstrap $_bootstrap;
    private HeaderServices $_headerService;
    private NavigationServices $_navigationService;
    private ValidationServices $_validationService;

    public function __construct(
        Bootstrap $bootstrap,
        HeaderServices $headerService,
        NavigationServices $navigationService,
        ValidationServices $validationService
    ) {
        $this->_bootstrap = $bootstrap;
        $this->_headerService = $headerService;
        $this->_navigationService = $navigationService;
        $this->_validationService = $validationService;
    }

    public function index(): void
    {
        $db = $this->_bootstrap->getDB();
        $subjects = $this->_bootstrap->getSubjects();
        $selectedSubject = $this->_bootstrap->getSelectedSubject();
        $subjParam = $_GET['subj'] ?? null;
        $pageParam = $_GET['page'] ?? null;

        if (intval($subjParam) === 0) {
            $this->redirect("content.php");
        }

        // Handle form POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Required + length checks
            $errors = $this->_validationService->validateRequiredFields(
                ['menu_name', 'position', 'visible']
            );
            $errors = array_merge(
                $errors,
                $this->_validationService->validateMaxLengths(['menu_name' => 30])
            );

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $this->redirect("edit_subject.php?subj=" . urlencode($subjParam));
            }

            $menuName = $_POST['menu_name'];
            $position = (int) $_POST['position'];
            $visible = (int) $_POST['visible'];

            $result = $db->update_subject($subjParam, $menuName, $position, $visible);

            if ($result > 0) {
                $_SESSION['message'] = "The subject was successfully updated!";
            } else {
                $_SESSION['message'] = "No changes were made.";
            }

            $this->redirect("edit_subject.php?subj=" . urlencode($subjParam));
        }

        // Render the template
        echo $this->_headerService->getHeader("edit_subject");
        ?>
        <table id="structure" class="structure">
            <tr>
                <td id="navigation" class="navigation">
                    <?php
                    echo $this->_navigationService->renderNavigation(
                        $subjects,
                        $subjParam,
                        $pageParam,
                        $db
                    );
                    ?>
                </td>
                <td id="page" class="page">
                    <h2 class="form-title">
                        Edit Subject: <?php echo htmlspecialchars($selectedSubject['menu_name']) ?>
                    </h2>

                    <?php if (!empty($_SESSION['errors'])) : ?>
                        <div class="error-messages">
                            <ul>
                                <?php foreach ($_SESSION['errors'] as $errorField): ?>
                                    <li>
                                        <?php
                                        switch ($errorField) {
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
                                            echo "Unknown error with $errorField.";
                                        }
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['message'])) : ?>
                        <div class="flash-message">
                            <?php echo htmlspecialchars($_SESSION['message']) ?>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <form action="edit_subject.php?subj=<?php echo urlencode($selectedSubject['id']) ?>"
                          method="post" class="form-subject">

                        <div class="form-group">
                            <label for="menu_name">Subject Name:</label>
                            <input type="text" name="menu_name" id="menu_name" class="input-text"
                                   value="<?php echo htmlspecialchars($selectedSubject['menu_name']) ?>" />
                        </div>

                        <div class="form-group">
                            <label for="position">Position:</label>
                            <select name="position" id="position" class="input-select">
                                <?php
                                $subjectCount = count($subjects);
                                for ($count = 1; $count <= $subjectCount + 1; $count++): ?>
                                    <option value="<?php echo $count ?>"
                                        <?php echo ($selectedSubject['position'] === $count) ? "selected" : "" ?>>
                                        <?php echo $count ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Visible:</label>
                            <label class="radio-option">
                                <input type="radio" name="visible" value="0"
                                    <?php echo ($selectedSubject['visible'] === 0) ? "checked" : "" ?> />
                                No
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="visible" value="1"
                                    <?php echo ($selectedSubject['visible'] === 1) ? "checked" : "" ?> />
                                Yes
                            </label>
                        </div>

                        <div class="form-actions">
                            <div class="button-container">
                                <input type="submit" name="submit" value="Edit Subject" class="btn btn-primary" />
                                <a href="delete_subject.php?subj=<?php echo urlencode($selectedSubject['id']) ?>"
                                   onclick="return confirm('Are you sure?');"
                                   class="btn btn-secondary">Delete Subject</a>
                            </div>
                            <div class="new-page-link-container">
                                <a href="new_page.php?subj=<?php echo urlencode($selectedSubject['id']) ?>">
                                    Add new page for this subject
                                </a>
                            </div>
                        </div>
                    </form>

                    <br/>
                    <a href="content.php" class="btn btn-secondary">Cancel</a>
                </td>
            </tr>
        </table>
        <?php
        include_once __DIR__ . "/../../includes/footer.php";
    }

    private function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }
}
