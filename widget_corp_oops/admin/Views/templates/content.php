<table id="structure" class="structure">
    <tr>
        <td id="navigation" class="navigation">
            <?php
            echo $this->navigationService->renderNavigation(
                $subjects,
                $subjParam,
                $pageParam,
                $pageModel,
            );
            ?>
            <br/>
            <a href="new_subject.php">+ Add a new subject</a>
        </td>
        <td id="page" class="page">
            <h2 class="title">
                <?php
                if ($selectedSubject) {
                    echo htmlspecialchars($selectedSubject['menu_name']);
                } elseif ($selectedPage) {
                    $fullContent = $selectedPage['content'];
                    $limit       = 100;
                    if (strlen($fullContent) > $limit) {
                        $excerpt = substr($fullContent, 0, $limit) . '...';
                    } else {
                        $excerpt = $fullContent;
                    }
                    echo '<div>' . htmlspecialchars($selectedPage['menu_name']) . '</div>';
                    echo '<br/>';
                    echo "<p class='page-excerpt'>" . htmlspecialchars($excerpt) . '</p>';
                    echo "<a href='edit_page.php?page={$selectedPage['id']}'>Edit Page</a>";
                } else {
                    echo 'Select a subject or page to edit';
                }
                ?>
            </h2>
        </td>
    </tr>
</table>
