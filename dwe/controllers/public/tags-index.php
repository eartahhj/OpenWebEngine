<?php

require_once $_SERVER['APP_ROOT'] . 'models/tags.php';

$tags = new \Tags();
$tags->setOrderBy('tag');
$tags->setRecords($tags->getAllRecords());
$tags->prepareList();
