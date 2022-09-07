<?php

class Dispatcher
{
    public static function returnControllerFromRouter(): Controller
    {
        $controller = Router::getControllerName() . 'Controller';

        if (!class_exists($controller)) {
            throw new LogicException('Could not load controller.');
            return null;
        }

        return new $controller();
    }
}

class DispatcherAdmin
{
    public static function returnControllerFromRouter(): Controller
    {
        $controller = RouterAdmin::getControllerName() . 'Controller';

        if (!class_exists($controller)) {
            throw new LogicException('Could not load controller.');
            return null;
        }

        return new $controller();
    }
}
