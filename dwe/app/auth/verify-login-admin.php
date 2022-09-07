<?php
$username = '';
$password = '';

$redirectUrl = '';

if (isset($_REQUEST['url']) and $_REQUEST['url']) {
    $redirectUrl = urldecode($_REQUEST['url']);
    if ($redirectUrl[0] != '/') {
        $redirectUrl = '';
    }
}

if (isset($_POST['username']) and $_POST['username']) {
    $username = mb_strtolower($_POST['username']);
}
if (isset($_POST['password']) and $_POST['password']) {
    $password = $_POST['password'];
}

if ($username and $password) {
    if (!$recordAdmin = $_admin->getRecordByUsername($username)) {
        $_messages->add(ALERT_TYPE_ERROR, 'Incorrect username or password, or user not enabled.');
        $_messages->add(ALERT_TYPE_DEBUG, 'Username not found');
    } else {
        $_admin->setDataByObject($recordAdmin);
        if (!$_admin->isPasswordCorrect($password)) {
            $_messages->add(ALERT_TYPE_ERROR, 'Incorrect username or password, or user not enabled.');
            $_messages->add(ALERT_TYPE_DEBUG, 'Wrong password');
        } elseif (!$_admin->isEnabled()) {
            // TODO: testing login when user has been disabled
            $_messages->add(ALERT_TYPE_ERROR, 'Incorrect username or password, or user not enabled.');
            $_messages->add(ALERT_TYPE_DEBUG, 'User has been disabled');
        } else {
            $_admin->login();
            if ($redirectUrl) {
                header("Location: {$_pagina->serverURL}{$redirectUrl}");
            } else {
                $_messages->add(ALERT_TYPE_CONFIRM, 'Succesfully logged in. Welcome!');
            }
        }
    }
} else {
    $_messages->add(ALERT_TYPE_ERROR, 'Please insert username and password.');
}
