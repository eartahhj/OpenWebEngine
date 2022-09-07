<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-header.php';
?>

<?php if ($_admin->isLogged() and $_admin->isEnabled()):?>
<div id="admin-bar">
    <div class="container">
        <ul class="grid">
            <li>
                <a href="/admin/"><?=_('Admin panel')?></a>
            </li>
            <li>
                <a href="/admin/pages/edit/<?=$_page->getId()?>"><?=_('Edit this page')?></a>
            </li>
            <li>
                <a href="/admin/pages/create/<?=$_page->getId()?>"><?=_('New page')?></a>
            </li>
            <li>
                <a href="/admin/pages/list/<?=$_page->getId()?>"><?=_('All pages')?></a>
            </li>
        </ul>
    </div>
</div>
<?php endif?>

<section id="page-content">

    <header id="page-content-header-grid" class="<?=$_page->getImage() ? 'grid' : 'only-text'?>">
        <?php if ($_page->getImage()):?>
        <div id="page-content-main-image" class="grid-col">
            <figure>
                <img src="/uploads/<?=$_page->getImage()?>" alt="<?=$_page->getPropertyMultilang('title')?>" width="800" height="800">
            </figure>
        </div>
        <?php endif?>
        <div class="grid-col">
            <div id="page-content-header-intro">
                <h1><?=$_page->getPropertyMultilang('title')?></h1>
                <table>
                    <thead id="page-timestamp">
                        <tr>
                            <th><?=_('Published')?></th>
                            <td><?=$dateCreation?></td>
                        </tr>
                        <tr>
                            <th><?=_('Updated')?></th>
                            <td><?=$dateModified?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pageCategories) and !$_page->hasCategory(1000)): ?>
                            <tr id="page-categories">
                                <th><?=_('Categories')?></th>
                                <td>
                                    <ul>
                                        <?php $i = 1?>
                                        <?php foreach ($_page->getCategories() as $pageCategory):?>
                                            <li>
                                                <a href="<?=$pageCategory->getFullUrl()?>"><?=$pageCategory->returnDefaultTitle()?></a><?=($i < count($_page->getCategories()) ? ',' : '')?>
                                            </li>
                                            <?php $i++?>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($pageTags)):?>
                            <tr id="page-tags">
                                <th><?=_('Tags')?></th>
                                <td>
                                    <ul>
                                        <?php foreach ($pageTags as $tag): ?>
                                            <li>
                                                <a href="<?=Config::$baseURLLanguage?>tags/<?=htmlspecialchars($tag)?>"><?=htmlspecialchars($tag)?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endif?>
                    </tbody>
                </table>

            </div>
        </div>
    </header>

    <?php if (!$_page->hasCategory(1000)):?>
    <div id="breadcrumb" class="container">
        <?php include $_SERVER['APP_ROOT'] . 'views/public/breadcrumb.php'?>
    </div>
    <?php endif?>

    <?php if (!$_page->hasCategory(1000)):?>
        <?php if ($ad = $pageAdTop):?>
            <div id="aff-top" class="container">
                <?php include $_SERVER['APP_ROOT'] . 'views/public/ad.php';?>
            </div>
        <?php endif?>
    <?php endif?>

    <article id="page-content-text" class="container">
        <?=$html . "\n"?>
    </article>
</section>

<?php if (!$_page->hasCategory(1000)):?>
    <?php if ($ad = $pageAdBottom):?>
        <div class="container" id="aff-bottom">
            <?php include $_SERVER['APP_ROOT'] . 'views/public/ad.php';?>
        </div>
    <?php endif?>
<?php /*
<div id="page-ads" style="text-align:center;">
    <div class="container">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6766935573967740" crossorigin="anonymous"></script>
        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-6766935573967740" data-ad-slot="5327877596" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <script>
        window.addEventListener('load', (event) => {
            (adsbygoogle = window.adsbygoogle || []).push({});
        });
        </script>
    </div>
</div>
*/?>

<section id="page-comments">
    <div class="container">
        <p class="h2"><?=_('Leave a comment')?></p>
        <p><?=_('All comments will be subject to approval after being sent. They might be published after several hours.')?></p>
        <p><?=_('Fields marked with * are mandatory. Email is optional and will not be published in any case.')?></p>
        <?php if (empty($comments)):?>
            <p><?=_('No comments have been written so far on this article. Be the first to share your thoughts!')?></p>
        <?php endif?>
        <?php
        echo $form->generateAndReturnHtml();

        if (!empty($comments)):
        ?>
        <ul>
            <?php
            foreach($comments as $comment):
            ?>
            <li class="page-comment">
                <h6><?=$comment->getDateCreation()?></h6>
                <h4><?=_('Author:')?> <?=htmlspecialchars($comment->getAuthorName())?></h4>
                <p><?=_('Comment:')?> <?=htmlspecialchars($comment->getText())?></p>
            </li>
            <?php
            endforeach;
            ?>
            </ul>
        <?php
        endif;
        ?>
    </div>
</section>
<?php endif?>

<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-footer.php';
