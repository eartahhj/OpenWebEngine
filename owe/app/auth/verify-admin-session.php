<?php
$_adminToken = '';

if (isset($_COOKIE[Admin::getCookieName()]) and $_COOKIE[Admin::getCookieName()]) {
    $_adminToken = $_COOKIE[Admin::getCookieName()];
}

$_admin = new Admin();

if ($_adminToken) {
    $tokenAdmin = new AdminToken();
    if ($_admin->isAdminLoggedWithThisToken($_adminToken)) {
        $recordAdmin = $_admin->getRecordById($_admin->token->getAdminId());
        $_admin->setDataByObject($recordAdmin);
        $_admin->setLogged(true);
        if (!$_admin->isEnabled()) {
            $_messages->add(ALERT_TYPE_DEBUG, 'This admin user has been disabled');
            $_admin->setLogged(false);
        }
    }
}
