<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Widget Corp</title>
    <link rel="stylesheet" href="/widget_corp/widget_corp_oops/stylesheets/frontend.css">
</head>
<body>
    <div id="header">
        <h1>Widget Corp</h1>
    </div>
    <div id="main">
        <div id="navigation">
            <?php echo $navigationHtml; ?>
        </div>
        <div id="page">
            <?php if ($selected['page']) : ?>
                <h2><?php echo htmlspecialchars($selected['page']['menu_name']); ?></h2>
                <p><?php echo $selected['page']['content']; ?></p>
            <?php elseif ($selected['subject']) : ?>
                <h2><?php echo htmlspecialchars($selected['subject']['menu_name']); ?></h2>
                <p>No page selected.</p>
            <?php else: ?>
                <h2>Welcome to Widget Corp!</h2>
                <p>Please select a subject or page.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="/widget_corp/widget_corp_oops/javascripts/navigation.js"></script>

</body>
</html>
