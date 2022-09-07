<?php

$action = Router::getAction();

if (!$action or $action == 'index') {
    $action = 'list';
}

if ($action == 'create' or $action == 'edit') {
    Router::redirectTo('/admin/filesmanager/manage');
}

if ($action != 'list') {
    exit();
}

require_once $_SERVER['APP_ROOT'] . 'models/files.php';
require_once $_SERVER['APP_ROOT'] . 'models/templates.php';

$_template = new TemplateAdmin();

$files = new Files();
$files->setOrderBy('title_' . $_language);
$files->setRecords($files->getAllRecords());
$files->prepareList();

$filesList = $files->getCompleteList();
