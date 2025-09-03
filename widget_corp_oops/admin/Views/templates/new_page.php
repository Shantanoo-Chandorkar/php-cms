<table id="structure" class="structure">
    <tr>
        <td id="navigation" class="navigation">
            <?php
            echo $this->navigationServices->renderNavigation(
                $subjects,
                $selected_subject['id'] ?? null,
                $selected_page['id'] ?? null,
                $this->pageModel
            );
            ?>
        </td>
        <td id="page" class="page">
            <h2 class="form-title">Create New Page in: <?php echo $selected_subject['menu_name']; ?></h2>

            <?php require __DIR__ . '/../partials/errors.php'; ?>
            <?php require __DIR__ . '/../partials/messages.php'; ?>

            <form action="new_page.php?subj=<?php echo urlencode($selected_subject['id']); ?>" method="post" class="form-subject">
                <div class="form-group">
                    <label for="menu_name">Page Name:</label>
                    <input type="text" name="menu_name" id="menu_name" class="input-text" value="" />
                </div>
                
                <div class="form-group">
                    <label for="position">Position:</label>
                    <select name="position" id="position" class="input-select">
                        <?php
                        $page_count = $this->pageModel->countPagesForSubject($selected_subject['id']);
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
