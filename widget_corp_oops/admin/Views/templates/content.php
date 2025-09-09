<div id="structure" class="structure">

    <!-- Navigation sidebar -->
    <nav id="navigation" class="navigation" aria-label="Admin navigation">
        <?php
        echo $this->navigationService->renderNavigation(
            $subjects,
            $subjParam,
            $pageParam,
            $pageModel
        );
        ?>
        <a href="new_subject.php">+ Add a new subject</a>
    </nav>

    <!-- Main content area -->
    <section id="page" class="page" tabindex="-1">
        <h2 class="title">
            <?php
            if ($selectedSubject) {
                echo htmlspecialchars($selectedSubject['menu_name']);
            } elseif ($selectedPage) {
                $fullContent = $selectedPage['content'];
                $excerpt     = strlen($fullContent) > 100
                    ? substr($fullContent, 0, 100) . '...'
                    : $fullContent;

                echo '<div>' . htmlspecialchars($selectedPage['menu_name']) . '</div>';
                echo "<p class='page-excerpt'>" . htmlspecialchars($excerpt) . '</p>';
                echo "<a href='edit_page.php?page={$selectedPage['id']}'>Edit Page</a>";
            } else {
                echo 'Select a subject or page to edit';
            }
            ?>
        </h2>
    </section>

</div>
