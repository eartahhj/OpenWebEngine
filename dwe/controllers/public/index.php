<?php
require_once $_SERVER['APP_ROOT'] . 'models/pages.php';

$_page = new Page();

require_once $_SERVER['APP_ROOT'] . 'models/templates.php';

$_template = new TemplateHomepage();

if (!$_template->loadTemplate()) {
    $_messages->add(ALERT_TYPE_ERROR, _('Template was not found. Did you configure the correct template in the view?'));
    $_template->exit();
}

if (!$_page->loadHomepage()) {
    require_once $_SERVER['APP_ROOT'] . 'views/public/404-notfound.php';
}

$matomoTracker->doTrackPageView($_page->returnDefaultTitle());

if ($_page->getPropertyMultilang('html')) {
    $html = $_page->getPropertyMultilang('html');
} elseif ($_page->getHtml()) {
    $html = $_page->getHtml();
}

if ($html) {
    $html = $_page->convertHtmlShortcodes($html);
} else {
    require_once $_SERVER['APP_ROOT'] . 'views/public/404-notfound.php';
}

$pageCategories = new PageCategories();
$pageCategories->setRecords($pageCategories->getAllRecords());
$pageCategories->prepareList();

$lastArticles = new Pages();
// $lastArticles->setRecords($lastArticles->getLastArticles(12));

$lastVideos = '';
