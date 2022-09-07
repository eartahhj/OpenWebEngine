<?php
require_once $_SERVER['APP_ROOT'].'models/pages.php';
require_once $_SERVER['APP_ROOT'].'models/templates.php';

$_template = new TemplateAdmin;

$action = Router::getAction();

if($_admin->logout()) {
    $_messages->add(ALERT_TYPE_CONFIRM, 'Logged out Succesfully');
}

require_once $_SERVER['APP_ROOT'].'views/templates/template-admin-header.php';
?>
<h1>Logout</h1>

<?php
require_once $_SERVER['APP_ROOT'].'views/templates/template-admin-footer.php';
