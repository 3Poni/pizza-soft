<?php

namespace vendor\App;

use vendor\App\Contracts\MiddlewareInterface;
use vendor\App\Router\Route;
use vendor\App\Router\RouteDispatcher;
use vendor\Response\Response;

class App
{
    public array $middlewareList = [];
    private static array $routes = [];

    public function setMiddleware($name, MiddlewareInterface $middleware): App
    {
        $this->middlewareList[] = [ $name => $middleware ];
        return $this;
    }

    public function run()
    {
        $requestMethod = ucfirst(strtolower($_SERVER['REQUEST_METHOD']));
        $methodName = 'getRoutes' . $requestMethod;
        foreach (Route::$methodName() as $routeConfiguration) {
            $routeDispatcher = new RouteDispatcher($routeConfiguration, $this->middlewareList);
            $routeDispatcher->process();
            self::$routes [] = $routeDispatcher->getRoute();
        }
        if (!in_array(urldecode($_SERVER['REQUEST_URI']), self::$routes)) {
            (new Response())->setStatusCode(404)->setMessage('resource not found')->send();
        }
    }

}


