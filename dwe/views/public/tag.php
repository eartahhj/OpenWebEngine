<?php require_once $_SERVER['APP_ROOT'] . 'views/templates/template-header.php'?>

    <div id="category-index-content" class="container">
        <h1><?=_('Articles for:')?> <em><?=htmlspecialchars($tag)?></em></h1>
        <?php if (!empty($articles)): ?>
            <div id="category-articles" class="elements-list">
                <ul>
                    <?php foreach ($articles as $id => $article): ?>
                        <li>
                            <a href="<?=Config::$baseURLLanguage . $article->fullUrl?>">
                                <figure>
                                    <img src="/uploads/<?=$article->image?>" alt="<?=htmlspecialchars($article->title)?>" loading="lazy" width="800" height="800">
                                    <figcaption>
                                        <h2><?=htmlspecialchars($article->title)?></h2>
                                    </figcaption>
                                </figure>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif?>
    </div>

<?php require_once $_SERVER['APP_ROOT'] . 'views/templates/template-footer.php'?>
