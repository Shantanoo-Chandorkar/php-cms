<ul class="subjects">
    <?php foreach ($subjects as $subject) : ?>
        <?php $active_subject = ($subj_param === $subject['id']) ? ' active' : ''; ?>
        <li class="subject-item<?php echo $active_subject; ?>">
            <a href="edit_subject.php?subj=<?php echo urlencode((string) $subject['id']); ?>">
                <?php echo htmlspecialchars($subject['menu_name']); ?>
            </a>
            <?php
            $pages = $pageModel->getPagesBySubjectId($subject['id']);
            if ($pages) :
                ?>
                <ul class="pages">
                    <?php foreach ($pages as $page) : ?>
                        <?php $active_page = ($page_param === $page['id']) ? ' active' : ''; ?>
                        <li class="page-item<?php echo $active_page; ?>">
                            <a href="edit_page.php?page=<?php echo urlencode((string) $page['id']); ?>">
                                <?php echo htmlspecialchars($page['menu_name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
