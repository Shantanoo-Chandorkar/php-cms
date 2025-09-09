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
            $pageModel
        );
        ?>
    </nav>

    <!-- Main content area -->
    <section id="page" class="page" tabindex="-1">

        <h2 class="form-title">Add Subject</h2>

        <!-- Error messages -->
        <?php require 'Views/partials/errors.php'; ?>
        <?php require 'Views/partials/messages.php'; ?>

        <!-- Form -->
        <form action="new_subject.php" method="post" class="form-subject">

            <div class="form-group">
                <label for="menu_name">Subject Name:</label>
                <input type="text" name="menu_name" id="menu_name" class="input-text"
                       aria-describedby="<?php echo !empty($errorSession) ? 'form-error' : ''; ?>" />
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

            <fieldset class="form-group">
                <legend>Visible:</legend>
                <label class="radio-option">
                    <input type="radio" name="visible" value="0" /> No
                </label>
                <label class="radio-option">
                    <input type="radio" name="visible" value="1" /> Yes
                </label>
            </fieldset>

            <div class="form-actions">
                <input type="submit" value="Add Subject" class="btn btn-primary" />
                <a href="content.php" class="btn btn-secondary">Cancel</a>
            </div>

        </form>

    </section>

</div>
