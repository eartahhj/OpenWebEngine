<?php

require_once $_SERVER['APP_ROOT'] . 'models/pages.php';

$tag = '';

if (!$tag = Router::getUrlPiece(2)) {
    exit();
}

$tagEscaped = Database::escapeLiteral('%' . $tag . '%');

$query = <<<SQL
SELECT
    pages.id AS page_id,
    pages.title_{$_language} AS page_title,
    pages.url_{$_language} AS page_url,
    pages.image AS page_image,
    page_categories.id AS category_id,
    page_categories.title_{$_language} AS category_title,
    page_categories.url_{$_language} AS category_url
FROM pages LEFT JOIN link_pages_categories ON pages.id = link_pages_categories.id_page RIGHT JOIN page_categories ON page_categories.id = link_pages_categories.id_category WHERE NOT pages.hidden_{$_language} AND NOT page_categories.hidden_{$_language} AND pages.tags_{$_language} LIKE {$tagEscaped}
ORDER BY page_title ASC
SQL;

$articles = [];

if ($result = Database::query($query)) {
    while ($record = Database::fetch($result)) {
        $article = new \stdClass();
        $article->title = $record->page_title;
        $article->url = $record->page_url . '-' . $record->page_id;
        $article->categoryTitle = $record->category_title;
        $article->categoryUrl = $record->category_url . '-' . $record->category_id;
        $article->fullUrl = $article->categoryUrl . '/' . $article->url;
        $article->image = $record->page_image;
        $articles[$record->page_id] = $article;
        unset($article);
    }

    Database::freeResult($result);
}

if (empty($articles)) {
    require_once $_SERVER['APP_ROOT'] . 'views/public/404-notfound.php';
} else {
    $_page = new Page();
    $_page->setTitle(_('Articles for:') . ' ' . htmlspecialchars($tag));

    $matomoTracker->doTrackPageView($_page->returnDefaultTitle());

    require_once $_SERVER['APP_ROOT'] . 'models/templates.php';

    $_template = new \TemplateStandard();

    if ($_admin->isLogged() and $_admin->isEnabled()) {
        $_template->addCss('/css/style-adminbar.css');
    }
}
