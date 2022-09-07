<?php

require_once $_SERVER['APP_ROOT'] . 'models/pages.php';

$_page = new PageCategoryIndex();

if (!$_page->loadDataByUrl() or $_page->getId() == 1000) {
    require_once $_SERVER['APP_ROOT'] . 'views/public/404-notfound.php';
}

if (!$_page->returnDefaultTitle()) {
    require_once $_SERVER['APP_ROOT'] . 'views/public/404-notfound.php';
}

$matomoTracker->doTrackPageView($_page->returnDefaultTitle());

$articles = $_page->getAssociatedPages();

$html = '';

if ($_page->getPropertyMultilang('html')) {
    $html = $_page->getPropertyMultilang('html');
} elseif ($_page->getHtml()) {
    $html = $_page->getHtml();
}

require_once $_SERVER['APP_ROOT' ] . 'models/templates.php';

$_template = new TemplateCategoryIndex();
$_template->loadTemplate();
