<?php
    $errorSession = $this->sessionService->get('errors');
    $messageSession = $this->sessionService->get('message');
?>
<div id="structure" class="structure">

    <!-- Navigation sidebar -->
    <nav id="navigation" class="navigation" aria-label="Admin navigation">
        <?php echo $navigationHtml; ?>
    </nav>

    <!-- Main content area -->
    <section id="page" class="page" tabindex="-1">

        <h2 class="form-title">
            Edit Subject: <?php echo htmlspecialchars($selectedSubject['menu_name']); ?>
        </h2>

        <!-- Error messages -->
        <?php if (! empty($errorSession)) : ?>
            <div class="error-messages" role="alert" aria-live="assertive" tabindex="-1">
                <ul>
                    <?php foreach ($errorSession as $errorField) : ?>
                        <li>
                            <?php
                            switch ($errorField) {
                                case 'menu_name':
                                    echo htmlspecialchars('Subject name is required and must be at most 30 characters.');
                                    break;
                                case 'position':
                                    echo htmlspecialchars('Position field is required.');
                                    break;
                                case 'visible':
                                    echo htmlspecialchars('Visible field is required.');
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
        <?php if (! empty($messageSession)) : ?>
            <div class="flash-message" role="status" aria-live="assertive" tabindex="-1">
                <?php echo htmlspecialchars($messageSession); ?>
                <?php $this->sessionService->unset('message'); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="edit_subject.php?subj=<?php echo urlencode($selectedSubject['id']); ?>"
              method="post" class="form-subject">

            <div class="form-group">
                <label for="menu_name">Subject Name:</label>
                <input type="text" name="menu_name" id="menu_name" class="input-text"
                       value="<?php echo htmlspecialchars($selectedSubject['menu_name']); ?>"
                       aria-describedby="<?php echo !empty($errorSession) ? 'form-error' : ''; ?>" />
            </div>

            <div class="form-group">
                <label for="position">Position:</label>
                <select name="position" id="position" class="input-select">
                    <?php
                    $subjectCount = count($subjects);
                    for ($count = 1; $count <= $subjectCount + 1; $count++) :
                        ?>
                        <option value="<?php echo $count; ?>"
                            <?php echo ($selectedSubject['position'] === $count) ? 'selected' : ''; ?>>
                            <?php echo $count; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <fieldset class="form-group">
                <legend>Visible:</legend>
                <label class="radio-option">
                    <input type="radio" name="visible" value="0"
                        <?php echo ($selectedSubject['visible'] === 0) ? 'checked' : ''; ?> />
                    No
                </label>
                <label class="radio-option">
                    <input type="radio" name="visible" value="1"
                        <?php echo ($selectedSubject['visible'] === 1) ? 'checked' : ''; ?> />
                    Yes
                </label>
            </fieldset>

            <div class="form-actions">
                <div class="button-container">
                    <input type="submit" name="submit" value="Edit Subject" class="btn btn-primary" />
                    <a href="delete_subject.php?subj=<?php echo urlencode($selectedSubject['id']); ?>"
                        onclick="return confirm('Are you sure?');"
                        class="btn btn-secondary">Delete Subject</a>
                </div>
                <div class="new-page-link-container">
                    <a href="new_page.php?subj=<?php echo urlencode($selectedSubject['id']); ?>">
                        Add new page for this subject
                    </a>
                </div>
            </div>

        </form>

        <br/>
        <a href="content.php" class="btn btn-secondary">Cancel</a>

    </section>

</div>
