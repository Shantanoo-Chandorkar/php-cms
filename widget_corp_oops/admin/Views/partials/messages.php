<?php if (! empty($messageSession)) : ?>
    <div class="flash-message">
        <?php echo htmlspecialchars($messageSession); ?>
    </div>
    <?php $this->sessionService->unset('message'); // Clear after showing ?>
<?php endif; ?>
