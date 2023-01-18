<?php

# You can comment these lines in production if you want, so errors will not be displayed
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

define('_ENCODING', 'UTF-8');
define('LOGIN_SESSION_DURATION_SECONDS', 3600 * 72); # 3 days

mb_internal_encoding(_ENCODING);
mb_regex_encoding(_ENCODING);

$_language = 'en';
$_languages = ['it' => 'Italiano', 'en' => 'English'];
$_locales = ['it' => 'it', 'it_it' => 'it', 'en' => 'en', 'en_us' => 'en'];
$_dbConnectionString = '';
$_dbHost = 'db_host';
$_dbUser = 'db_user';
$_dbPassword = 'db_password';
$_dbName = 'db_name';
$_dbType = 'my'; # 'my' => mysql, 'pg' => postgresql
$_debugMode = false;
$_debugModeCode = 'yzxUFC9qtJkmHZGK';

require_once $_SERVER['APP_ROOT'] . 'language.php';

/*
// NOTE: Uncomment if you plan on using gettext
setlocale(LC_CTYPE, $_language);
setlocale(LC_MESSAGES, $_language);
setlocale(LC_TIME, $_language);
bindtextdomain('domain_name', '/your/path/to/locale');
textdomain('domain_name');
bind_textdomain_codeset('domain_name', _ENCODING);
*/

if (isset($_COOKIE['debug']) and $_COOKIE['debug'] == $_debugModeCode) {
    $_debugMode = true;
}

/*
// NOTE: Uncomment if you plan to use Matomo Server Side Tracking
// @link: https://www.gaminghouse.community/en/articles-1002/matomo-server-side-tracking-131
$matomoSiteId = 1;
$matomoUrl = 'https://matomo.yoursite.com';
$matomoToken = 'matomo_token';
$matomoPageTitle = '';

require_once $_SERVER['APP_ROOT'] . 'vendor/matomo-php/MatomoTracker.php';

$matomoTracker = new MatomoTracker($matomoSiteId, $matomoUrl);
$matomoTracker->setTokenAuth($matomoToken);
*/
