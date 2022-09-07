<?php

$action = Router::getAction();

if (!$action or $action == 'edit' or $action == 'index') {
    $action = 'list';
}

if ($action == 'create') {
    Router::redirectTo('/admin/pages/create');
}

if ($action != 'list') {
    exit();
}

require_once $_SERVER['APP_ROOT'] . 'models/pages.php';
require_once $_SERVER['APP_ROOT'] . 'models/templates.php';

$_template = new TemplateAdmin();

$pages = new Pages();
$pages->setOrderBy('id');
$pages->setRecords($pages->getAllRecords());
$pages->prepareList();

if ($pagesList = $pages->getCompleteList()) {
    $pagesAndCategories = $pages->getPagesAndCategories();
    $pages = $pagesAndCategories->getPages();
    $categories = $pagesAndCategories->getCategories();
}
