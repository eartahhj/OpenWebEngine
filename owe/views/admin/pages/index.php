<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-header.php';

if ($pagesList):
?>
    <h1><?=_('List of pages')?></h1>
    <h3><?=_('Uncategorized Pages')?></h3>
    <ul>
    <?php
    foreach ($pages as $page):
        if (!$page->getCategories()):
        ?>
        <li>
            <a href="/admin/pages/edit/<?=htmlspecialchars($page->getId())?>">
                <?=htmlspecialchars($page->getPropertyMultilang('title'))?>
            </a>
        </li>
        <?php
        endif;
    endforeach;
    ?>
    </ul>

    <?php
    foreach($categories as $category):?>
    <h3><?=htmlspecialchars($category->getPropertyMultilang('title'))?></h3>
    <ul>
        <?php foreach ($pages as $page):
            if ($page->hasCategory($category->getId())):
                ?>
            <li>
                <a href="/admin/pages/edit/<?=htmlspecialchars($page->getId())?>">
                    <?=htmlspecialchars($page->getPropertyMultilang('title'))?>
                </a>
            </li>
        <?php
            endif;
        endforeach;
        ?>
    </ul>
    <?php endforeach?>
<?php
else:
?>
<h1><?=_('No data found')?></h1>
<?php
endif;

require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-footer.php';
