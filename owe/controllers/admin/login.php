<?php

require_once $_SERVER['APP_ROOT'] . 'vendor/forms.php';
require_once $_SERVER['APP_ROOT'] . 'models/pages.php';
require_once $_SERVER['APP_ROOT'] . 'models/templates.php';

$_template = new TemplateAdmin();

$action = RouterAdmin::getAction();

$tokens = new AdminsTokens();
$tokens->deleteExpiredSessions();

require_once $_SERVER['APP_ROOT'] . 'app/form-templates/admin/login.php';

if (isset($_POST['login']) and $_POST['formID'] == $form->getId()) {
    if (!$form->validateFields()) {
        $_messages->add(ALERT_TYPE_ERROR, _('Login failed'));
    } else {
        $_admin = new Admin();
        $tokenAdmin = new AdminToken();

        require_once $_SERVER['APP_ROOT'] . 'app/auth/verify-login-admin.php';
    }
}
