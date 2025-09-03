<?php if (! empty($errorSession)) : ?>
    <div class="error-messages">
        <ul>
            <?php foreach ($errorSession as $error_field) : ?>
                <li>
                    <?php
                        // Customize messages per field
                    switch ($error_field) {
                        case 'menu_name':
                            echo 'Page name is required and must be at most 30 characters.';
                            break;
                        case 'position':
                            echo 'Position is required.';
                            break;
                        case 'visible':
                            echo 'Visible is required.';
                            break;
                        case 'content':
                            echo 'Content cannot be empty.';
                            break;
                        default:
                            echo "Unknown error with $error_field.";
                    }
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php $this->sessionService->unset('errors'); // Clear after showing ?>
<?php endif; ?>
