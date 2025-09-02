<?php if (! empty($_SESSION['message'])) : ?>
    <div class="flash-message">
        <?php echo htmlspecialchars($_SESSION['message']); ?>
    </div>
    <?php unset($_SESSION['message']); // Clear after showing ?>
<?php endif; ?>
