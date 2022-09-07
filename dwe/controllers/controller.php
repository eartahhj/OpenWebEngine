<?php

// NOTE: You can configure your controllers here, or just create single files like PageController.php
// and then require them here

abstract class Controller
{
    protected $url = '';
    protected $view = '';
    protected $viewFile = '';
    protected $controllerFile = '';

    public function loadController(): void
    {
        $controllerFile = $_SERVER['APP_ROOT'] . 'controllers/' . $this->controllerFile . '.php';

        if (!file_exists($controllerFile)) {
            throw new LogicException('Contoller file missing. Check controller.php configuration');
        } else {
            $this->controllerFile = $controllerFile;
        }
        return;
    }

    public function loadView(): void
    {
        $viewFile = $_SERVER['APP_ROOT'] . 'views/' . $this->view . '.php';

        if (!file_exists($viewFile)) {
            throw new LogicException('View file missing. Check views configuration');
        } else {
            $this->viewFile = $viewFile;
        }
        return;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getViewFile(): string
    {
        return $this->viewFile;
    }

    public function getControllerFile(): string
    {
        return $this->controllerFile;
    }
}

class NotFoundController extends Controller
{
    protected $controllerFile = 'public/404-notfound';
    protected $view = 'public/404-notfound';
}

class IndexController extends Controller
{
    protected $controllerFile = 'public/index';
    protected $view = 'public/index';
}

class PagesController extends Controller
{
    protected $controllerFile = 'public/page-single';
    protected $view = 'public/page-single';
}

class TagsController extends Controller
{
    protected $controllerFile = 'public/tag';
    protected $view = 'public/tag';
}

class TagsIndexController extends Controller
{
    protected $controllerFile = 'public/tags-index';
    protected $view = 'public/tags-index';
}

class PagesIndexController extends Controller
{
    protected $controllerFile = 'public/pages-index';
    protected $view = 'public/pages-index';
}

class AdminIndexController extends Controller
{
    protected $controllerFile = 'admin/index';
    protected $view = 'admin/index';
}

class AdminLoginController extends Controller
{
    protected $controllerFile = 'admin/login';
    protected $view = 'admin/login';
}

class AdminLogoutController extends Controller
{
    protected $controllerFile = 'admin/logout';
    protected $view = 'admin/logout';
}

class AdminPagesIndexController extends Controller
{
    protected $controllerFile = 'admin/pages/index';
    protected $view = 'admin/pages/index';
}

class AdminPagesEditController extends Controller
{
    protected $controllerFile = 'admin/pages/manage';
    protected $view = 'admin/pages/manage';
}

class AdminNewsController extends Controller
{
    protected $controllerFile = 'admin/news';
    protected $view = 'admin/news';
}

class AdminSettingsIndexController extends Controller
{
    protected $controllerFile = 'admin/settings/index';
    protected $view = 'admin/settings/index';
}

class AdminFilesManagerIndexController extends Controller
{
    protected $controllerFile = 'admin/filesmanager/index';
    protected $view = 'admin/filesmanager/index';
}

class AdminFilesManagerEditController extends Controller
{
    protected $controllerFile = 'admin/filesmanager/manage';
    protected $view = 'admin/filesmanager/manage';
}
