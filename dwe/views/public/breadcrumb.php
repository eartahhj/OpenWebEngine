<?php $i = 0?>
<nav>
    <span class="sr-only"><?=_('Breadcrumb')?></span>
    <ol class="breadcrumb">
    <?php foreach ($breadcrumb as $crumbUrl => $crumbTitle): ?>
        <li class="breadcrumb-item<?=($i == count($breadcrumb) - 1) ? ' active' : ''?>"<?=($i == count($breadcrumb) - 1) ? ' aria-current="page"' : ''?>>
            <a href="<?=$crumbUrl?>"><?=htmlspecialchars($crumbTitle)?></a>
        </li>
        <?php $i++?>
    <?php endforeach; ?>
    </ol>
</nav>
