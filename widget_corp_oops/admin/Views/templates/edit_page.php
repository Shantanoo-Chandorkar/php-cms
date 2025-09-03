<table id="structure" class="structure">
    <tr>
        <td id="navigation" class="navigation">
            <?php echo $navigationHtml ?>
        </td>
        <td id="page" class="page">
            <h2 class="form-title">Edit Page: <?php echo $selected_page['menu_name']; ?></h2>
            <?php
                $errorSession =  $this->sessionService->get('errors');
                $messageSession = $this->sessionService->get('message');
            ?>
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
                            $page_count = $this->pageModel->countPagesForSubject($selected_page['subject_id']);
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
                        <input type="radio" name="visible" value="0" <?php echo $selected_page['visible'] === 0 ? 'checked' : ''; ?> /> No
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="visible" value="1" <?php echo $selected_page['visible'] === 1 ? 'checked' : ''; ?> /> Yes
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
