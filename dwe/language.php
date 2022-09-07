<?php
$browserAcceptLanguage = '';
$userLanguage = '';

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $browserAcceptLanguage = mb_strtolower(locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']));
    $_language = $_locales[$browserAcceptLanguage] ?? '';
}

if (isset($_COOKIE['lang']) and $userLanguage = $_COOKIE['lang']) {
    $_language = $_languages[$userLanguage] ?? 'en';
}
