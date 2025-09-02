<table id="structure" class="structure">
    <tr>
        <td id="navigation" class="navigation">
            <?php
            echo $this->navigation_services->renderNavigation(
                $subjects,
                $selected_subject['id'] ?? null,
                $selected_page['id'] ?? null,
                $db ?? null
            );
            ?>
        </td>
        <td id="page" class="page">
            <h2 class="form-title">Add Subject</h2>
            <?php if (!empty($_SESSION['errors'])) : ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error) : ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>
            <form action="new_subject.php" method="post" class="form-subject">
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