<?php

require_once 'routes.php';

class Router
{
    protected static $url='';
    protected static $urlPieces=[];
    protected static $controllerName='';
    protected static $action='';
    protected static $params='';
    protected static $queryString='';

    protected static function explodeUrl(): void
    {
        self::$urlPieces = explode('/', self::$url);
        return;
    }

    protected static function setControllerNameByUrl($urlPiece=0): void
    {
        self::$controllerName = self::getUrlPiece($urlPiece);
        return;
    }

    protected static function setActionByUrlPiece(int $urlPiece=1): void
    {
        self::$action = self::getUrlPiece($urlPiece);
        return;
    }

    protected static function setParamsByUrlPiece(int $urlPiece=2): void
    {
        self::$params = self::getUrlPiece($urlPiece);
        return;
    }

    public static function loadRouter()
    {
        self::$url = WebRequest::getUrl();
        self::setQueryString();
        self::prepareUrl();
        return;
    }

    public static function getUrl(): string
    {
        return self::$url;
    }

    public static function getUrlPieces(): array
    {
        return self::$urlPieces;
    }

    public static function getUrlPiece(int $piece): string
    {
        return self::$urlPieces[$piece] ?? '';
    }

    public static function getControllerName(): string
    {
        return self::$controllerName;
    }

    public static function getAction(): string
    {
        return self::$action;
    }

    public static function getParams(): string
    {
        return self::$params;
    }

    public static function setQueryString(): void
    {
        self::$queryString = parse_url(self::$url, PHP_URL_QUERY);
        return;
    }

    public static function getQueryString(): string
    {
        if (!self::$queryString) {
            self::setQueryString();
        }
        return self::$queryString;
    }

    public static function removeQueryStringFromUrl()
    {
        if (!self::$queryString) {
            self::setQueryString();
        }
        self::$url = str_replace('?' . self::$queryString, '', self::$url);
    }

    public static function prepareUrl(): void
    {
        self::removeQueryStringFromUrl();
        self::$url = trim(self::$url);

        $urlLastChar = substr(self::$url, -1);

        if ($urlLastChar == '/') {
            self::$url = substr(self::$url, 0, -1);
        }

        self::explodeUrl();

        self::removeEmptyUrlPieces();

        return;
    }

    public static function getLastUrlPieceKey(): int
    {
        if (function_exists('array_key_last')) {
            $lastKey = array_key_last(self::$urlPieces);
        } else {
            $lastKey = count(self::$urlPieces) - 1;
        }

        return $lastKey;
    }

    public static function getLastUrlPieceValue(): string
    {
        return self::$urlPieces[self::$getLastUrlPieceKey()] ?? '';
    }

    public static function removeEmptyUrlPieces(): int
    {
        $newIndex = 0;
        $numberOfEmptyPieces = 0;
        $cleanPieces = [];

        if (count(self::$urlPieces) > 1) {
            foreach (self::$urlPieces as $pieceKey => $pieceValue) {
                if ($pieceValue == '') {
                    unset(self::$urlPieces[$pieceKey]);
                    $numberOfEmptyPieces++;
                } else {
                    $cleanPieces[$newIndex] = $pieceValue;
                    $newIndex++;
                }
            }

            self::$urlPieces = $cleanPieces;
        }

        return $numberOfEmptyPieces;
    }

    public static function removeLastUrlPieceIfEmpty(): bool
    {
        if (count(self::$urlPieces) > 1 and self::$getLastUrlPieceValue() == '') {
            unset(self::$urlPieces[self::$getLastUrlPieceKey()]);
            return true;
        }
        return false;
    }

    public static function analyzeUrlPrepareRouter(): void
    {
        global $_languages, $_language;

        if (!self::$urlPieces) {
            self::redirectTo('/' . $_language);
            return;
        }

        if (count(self::$urlPieces) == 1) {
            if (isset(self::$urlPieces[0])) {
                switch (self::$urlPieces[0]) {
                    case '':
                    case 'index.php':
                        self::redirectTo('/' . $_language);
                        break;
                    case isset($_languages[self::$urlPieces[0]]):
                        self::$controllerName = 'Index';
                        break;
                    default:
                        self::$controllerName = 'NotFound';
                        return;
                }
            }
        } elseif (count(self::$urlPieces) > 1) {
            switch (self::$urlPieces[1]) {
                case 'tags':
                    if (count(self::$urlPieces) == 2) {
                        self::$controllerName = 'NotFound';
                        return;
                    } else {
                        self::$controllerName = 'Tags';
                        break;
                    }
                    // no break
                default:
                    self::$controllerName = 'Pages';
                    break;
            }

            # This means the user is viewing a category without a specific page url (eg. "/en/articles/")
            if (count(self::$urlPieces) == 2) {
                self::$controllerName .= 'Index';
                return;
            }
        }

        return;
    }

    public static function redirectTo(string $url, int $httpResponseCode = null): void
    {
        header("Location: $url", true, $httpResponseCode);
        exit();
        return;
    }
}
