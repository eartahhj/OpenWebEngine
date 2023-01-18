<?php

class Config
{
    public const DEFAULT_HOMEPAGE_URL = '';

    public static $appName = 'OpenWebEngine';
    public static $appVersion = '1.0.0';
    public static $templatesDirectory = '';
    public static $cssFolder = '/css/';
    public static $javascriptFolder = '/js/';
    public static $baseURL = '/';
    public static $baseURLAdmin = '';
    public static $defaultHomepageUrl = '';
    public static $baseURLLanguage = '';
    public static $documentRoot = '';
    public static $publicHtmlDir = '';
    public static $uploadsAbsoluteDir = '/your/path/to/public_html/uploads/';
    public static $uploadsRelativeDir = '/uploads/';
    public static $mediaLibraryAbsoluteDir = '/your/path/to/public_html/medialibrary/';
    public static $mediaLibraryRelativeDir = '/medialibrary/';

    public function __construct()
    {
        global $_language;
        self::$defaultHomepageUrl = $_language;
        self::$baseURLLanguage = self::$baseURL . $_language . '/';
        self::$documentRoot = $_SERVER['APP_ROOT'];
        self::$publicHtmlDir = $_SERVER['APP_PUBLIC'];
        self::$templatesDirectory = $_SERVER['APP_ROOT'] . 'views/templates/';
        self::$baseURLAdmin = self::$baseURL . 'admin/';
    }
}

$_config = new Config();
