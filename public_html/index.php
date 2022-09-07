<?php

require_once $_SERVER['APP_ROOT'] . 'bootstrap.php';

require_once $_SERVER['APP_ROOT'] . 'routers/request.php';

$url = WebRequest::getUrl();
$urlPieces = explode('/', $url);
if (isset($_languages[$urlPieces[1]])) {
    $_language = $urlPieces[1];
} else {
    $_language = 'en';
}

require_once $_SERVER['APP_ROOT'] . 'routers/router.php';

Router::loadRouter();
Router::analyzeUrlPrepareRouter();

require_once $_SERVER['APP_ROOT'] . 'routers/dispatcher.php';

require_once $_SERVER['APP_ROOT'] . 'config/config.php';

require_once $_SERVER['APP_ROOT'] . 'core/functions.php';

require_once $_SERVER['APP_ROOT'] . 'core/alerts.php';

$_messages = new Alerts();

require_once $_SERVER['APP_ROOT'] . 'core/database.php';

require_once $_SERVER['APP_ROOT'] . 'models/model.php';

require_once $_SERVER['APP_ROOT'] . 'models/admin.php';

require_once $_SERVER['APP_ROOT'] . 'app/auth/verify-admin-session.php';

require_once $_SERVER['APP_ROOT'] . 'app/modules.php';

require_once $_SERVER['APP_ROOT'] . 'controllers/controller.php';

$_controller = Dispatcher::returnControllerFromRouter();
$_controller->loadController();
require_once $_controller->getControllerFile();

require_once $_SERVER['APP_ROOT'] . 'models/templates.php';

$_controller->loadView();
require_once $_controller->getViewFile();
