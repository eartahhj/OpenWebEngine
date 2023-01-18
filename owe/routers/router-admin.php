<?php

require_once 'router.php';

class RouterAdmin extends Router
{
    public function __construct()
    {
        parent::__construct();
    }

    final public static function analyzeUrlPrepareRouter(): void
    {
        if (!self::$urlPieces) {
            self::$controllerName = 'NotFound';
            return;
        }

        self::$controllerName = 'Admin';

        self::setActionByUrlPiece(2);
        self::setParamsByUrlPiece(3);

        if (!isset(self::$urlPieces[1])) {
            self::$controllerName .= 'Index';
        } else {
            switch (self::$urlPieces[1]) {
                case 'index':
                    self::$controllerName .= 'Index';
                break;

                case 'login':
                    self::$controllerName .= 'Login';
                break;

                case 'logout':
                    self::$controllerName .= 'Logout';
                break;

                case 'pages':
                    self::$controllerName .= 'Pages';

                    if (self::$action == 'edit' or self::$action == 'create') {
                        self::$controllerName .= 'Edit';
                    } else {
                        self::$controllerName .= 'Index';
                    }
                break;

                case 'news':
                    self::$controllerName .= 'News';
                break;

                case 'settings':
                    self::$controllerName .= 'SettingsIndex';
                break;

                case 'filesmanager':
                    self::$controllerName .= 'FilesManager';

                    if (self::$action == 'edit' or self::$action == 'create') {
                        self::$controllerName .= 'Edit';
                    } else {
                        self::$controllerName .= 'Index';
                    }
                break;

                default:
                    self::$controllerName = 'NotFound';
                break;
            }
        }

        return;
    }
}
