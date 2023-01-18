<?php if ($_debugMode):?>
    <?php if (!empty($tags)): ?>
        <ul>
            <?php foreach ($tags->getList() as $tag):?>
            <li><?=$tag->returnDefaultTitle()?></li>
            <?php endforeach?>
        </ul>
    <?php endif; ?>
<?php endif?>
