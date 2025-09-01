<?php
echo $header->render( 'staff' );
?>
<table id="structure" class="structure">
    <tr>
        <td id="navigation" class="navigation">
            <?php echo $navigation->renderUsersNavigation( $users, $userParam ); ?>
        </td>
        <td id="page" class="page">
            <h2 class="title">Staff Menu</h2>
            <p class="sub-title">Welcome to the staff menu</p>
            <ul class="menu">
                <li><a href="content.php">Manage Website Content</a></li>
                <li><a href="new_user.php">Add Staff User</a></li>
                <li><a href="auth/logout.php">Logout</a></li>
            </ul>
        </td>
    </tr>
</table>
<?php require __DIR__ . '/partials/footer.php'; ?>
