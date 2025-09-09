<?php
echo $this->headerServices->getHeader('staff', $this->sessionService);
?>

<!-- Flash message -->
<?php
$message = $this->sessionService->get('message') ?? '';
if (!empty($message)) : ?>
    <div class="flash-message" role="status" aria-live="polite" tabindex="-1">
        <?php echo htmlspecialchars($message); ?>
    </div>
    <?php $this->sessionService->unset('message'); ?>
<?php endif; ?>

<div id="structure" class="structure">

    <!-- Navigation sidebar -->
    <nav id="navigation" class="navigation" aria-label="Staff navigation">
        <?php echo $this->navigationServices->renderUsersNavigation($users, $userParam); ?>
    </nav>

    <!-- Main content area -->
    <section id="page" class="page" tabindex="-1">
        <h2 class="title">Staff Menu</h2>
        <p class="sub-title">Welcome to the staff menu</p>

        <ul class="menu" role="menu">
            <li role="none">
                <a role="menuitem" href="content.php">Manage Website Content</a>
            </li>
            <li role="none">
                <a role="menuitem" href="new_user.php">Add Staff User</a>
            </li>
            <li role="none">
                <a role="menuitem" href="../auth/logout.php">Logout</a>
            </li>
        </ul>
    </section>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
