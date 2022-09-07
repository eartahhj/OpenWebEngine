<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-header.php';
?>
<section>

    <article id="page-content" class="container">

    <?=$html . "\n"?>

    <?php if ($articles = $lastArticles->getLastArticles(16)): ?>
    <div id="last-articles" class="elements-list">
        <h2><?=translate(['it' => 'Ultimi articoli', 'en' => 'The new stuff'])?></h2>
        <ul>
            <?php foreach ($articles as $id => $article): ?>
                <li>
                    <a href="<?=Config::$baseURLLanguage . htmlspecialchars($article->category_url) . '-' . $article->category_id . '/' . htmlspecialchars($article->url . '-' . $id)?>">
                        <figure>
                            <img src="/uploads/<?=$article->image?>" alt="<?=htmlspecialchars($article->title)?>" width="800" height="800" loading="lazy">
                            <figcaption>
                                <h2><?=htmlspecialchars($article->title)?></h2>
                            </figcaption>
                        </figure>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    </article>
</section>

<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-footer.php';
