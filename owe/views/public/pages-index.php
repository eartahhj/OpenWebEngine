<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-header.php';
?>
<section>
    <article id="category-index-content" class="container">

    <h1><?=$_page->returnDefaultTitle()?></h1>

    <?=$html . "\n"?>

    <?php if ($articles): ?>
        <div id="category-articles" class="elements-list">
            <ul>
                <?php foreach ($articles as $id => $article): ?>
                    <li>
                        <a href="<?=Config::$baseURLLanguage . $_page->getFormattedUrl() . '/' . $article->page_url . '-' . $article->page_id?>">
                            <figure>
                                <img src="/uploads/<?=$article->image?>" alt="<?=htmlspecialchars($article->page_title)?>" loading="lazy" width="800" height="800">
                                <figcaption>
                                    <h2><?=htmlspecialchars($article->page_title)?></h2>
                                </figcaption>
                            </figure>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <p><?=_('Right now there is nothing in this category. Try again later.')?></p>
    <?php endif?>
    </article>
</section>

<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-footer.php';
?>
