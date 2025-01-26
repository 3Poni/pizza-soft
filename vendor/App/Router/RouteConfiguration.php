<?php

namespace vendor\App\Router;


class RouteConfiguration
{
    public string $route;
    public string $controller;
    public string $action;
    public ?string $middleware = null;

    public function __construct(string $route, string $controller, string $action)
    {
        $this->route = $route;
        $this->controller = $controller;
        $this->action = $action;
    }

    public function middleware(string $middleware)
    {
        $this->middleware .= ',' . $middleware;
    }
}


