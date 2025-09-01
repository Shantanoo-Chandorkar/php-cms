<?php

namespace Widget_Corps_Oops_Frontend\Services;

use Widget_Corps_Oops_Helper\DBConnection;

class NavigationService
{
    public function renderFrontendNavigation(array $subjects, ?int $subjId, ?int $pageId, DBConnection $db): string
    {
        $currentPage = null;
        if ($pageId) {
            $currentPage = $db->get_page_by_id($pageId);
        }

        ob_start();
        ?>
        <ul class="subjects">
            <?php foreach ($subjects as $subject): 
                $subjectId = (int)$subject['id'];
                $isActiveSubject = ($subjId === $subjectId) || ($currentPage && $currentPage['subject_id'] === $subjectId);
                $subjectClass = $isActiveSubject ? ' active' : '';
                $pagesId = "pages-" . $subjectId;
                $pages = $db->get_pages($subjectId);
                ?>
                <li class="subject-item<?php echo $subjectClass; ?>">
                    <button type="button" class="subject-toggle" data-target="<?php echo $pagesId; ?>" aria-expanded="<?php echo $isActiveSubject ? 'true' : 'false'; ?>">
                        <?php echo htmlspecialchars($subject['menu_name']); ?>
                    </button>

                    <?php if (!empty($pages)) : ?>
                        <ul id="<?php echo $pagesId; ?>" class="pages<?php echo $isActiveSubject ? ' show' : ''; ?>">
                            <?php foreach ($pages as $page): 
                                $isActivePage = ($currentPage && $page['id'] === $currentPage['id']) || ($pageId === $page['id']);
                                $pageClass = $isActivePage ? ' active' : '';
                                ?>
                                <li class="page-item<?php echo $pageClass; ?>">
                                    <a href="index.php?page=<?php echo $page['id']; ?>&subj=<?php echo $subjectId; ?>">
                                        <?php echo htmlspecialchars($page['menu_name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
        return ob_get_clean();
    }
}
