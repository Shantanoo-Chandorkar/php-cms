<?php
    $errorSession = $this->sessionService->get('errors');
    $messageSession = $this->sessionService->get('message');
?>
<div id="structure" class="structure">

    <!-- Navigation sidebar -->
    <nav id="navigation" class="navigation" aria-label="Admin navigation">
        <?php
        echo $this->navigationServices->renderNavigation(
            $subjects,
            $selected_subject['id'] ?? null,
            $selected_page['id'] ?? null,
            $this->pageModel
        );
        ?>
    </nav>

    <!-- Main content area -->
    <section id="page" class="page" tabindex="-1" aria-labelledby="page-title">

        <h2 id="page-title" class="form-title">
            Create New Page in: <?php echo htmlspecialchars($selected_subject['menu_name']); ?>
        </h2>

        <!-- Error messages -->
        <?php if (!empty($errorSession)) : ?>
            <div id="form-errors" class="error-messages" role="alert" aria-live="assertive">
                <ul>
                    <?php foreach ($errorSession as $errorField) : ?>
                        <li>
                            <?php
                            switch ($errorField) {
                                case 'menu_name':
                                    echo htmlspecialchars('Page name is required.');
                                    break;
                                case 'position':
                                    echo htmlspecialchars('Position field is required.');
                                    break;
                                case 'visible':
                                    echo htmlspecialchars('Visible field is required.');
                                    break;
                                case 'content':
                                    echo htmlspecialchars('Content is required.');
                                    break;
                                default:
                                    echo htmlspecialchars("Unknown error with {$errorField}.");
                            }
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php $this->sessionService->unset('errors'); ?>
        <?php endif; ?>

        <!-- Flash message -->
        <?php if (!empty($messageSession)) : ?>
            <div id="flash-message" class="flash-message" role="status" aria-live="polite">
                <?php echo htmlspecialchars($messageSession); ?>
                <?php $this->sessionService->unset('message'); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="new_page.php?subj=<?php echo urlencode($selected_subject['id']); ?>" method="post" class="form-subject">

            <div class="form-group">
                <label for="menu_name">Page Name:</label>
                <input type="text" name="menu_name" id="menu_name" class="input-text"
                       aria-required="true"
                       aria-describedby="<?php echo !empty($errorSession) ? 'form-errors' : ''; ?>" />
            </div>

            <div class="form-group">
                <label for="position">Position:</label>
                <select name="position" id="position" class="input-select" aria-required="true">
                    <?php
                    $page_count = $this->pageModel->countPagesForSubject($selected_subject['id']);
                    for ($count = 1; $count <= $page_count + 1; $count++) {
                        echo "<option value='{$count}'>{$count}</option>";
                    }
                    ?>
                </select>
            </div>

            <fieldset class="form-group">
                <legend>Visible</legend>
                <label class="radio-option">
                    <input type="radio" name="visible" value="0" checked /> No
                </label>
                <label class="radio-option">
                    <input type="radio" name="visible" value="1" /> Yes
                </label>
            </fieldset>

            <div class="form-group">
                <label for="content">Page Content:</label>
                <textarea name="content" id="content" class="input-text" rows="15" aria-required="true"
                          aria-describedby="<?php echo !empty($errorSession) ? 'form-errors' : ''; ?>"></textarea>
            </div>

            <div class="form-actions">
                <input type="submit" name="submit" value="Create Page" class="btn btn-primary" />
                <a href="content.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

    </section>
</div>
