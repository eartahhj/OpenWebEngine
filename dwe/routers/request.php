<?php

class WebRequest
{
    protected static $url = '';

    public static function getUrl(): string
    {
        self::$url = $_SERVER['REQUEST_URI'];
        return self::$url;
    }
}
