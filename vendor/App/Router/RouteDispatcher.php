<?php

namespace vendor\App\Router;

use vendor\Response\Response;

class RouteDispatcher
{
    private array $middlewareAllowed;
    private array $paramMap = [];
    private array $paramRequestMap = [];
    private RouteConfiguration $routeConfiguration;

    public function __construct(RouteConfiguration $routeConfiguration, $middlewareAllowed)
    {
        $this->routeConfiguration = $routeConfiguration;
        $this->middlewareAllowed = $middlewareAllowed;
    }

    public function process()
    {
        $this->setParamMap();
        $this->run();
    }

    public function getRoute(): string
    {
        return $this->routeConfiguration->route;
    }

    private function setParamMap()
    {
        $routeArray = explode('/', $this->routeConfiguration->route);
        $requestedRoute = explode('/', $_SERVER['REQUEST_URI']);
        $this->paramMap = $routeArray;
        $this->paramRequestMap = array_slice($routeArray, 1);

        // bind uri dynamic params to _GET
        foreach ($routeArray as $key => $route_part) {
            if(preg_match("/{+\w+}/", $route_part)) {
                $this->paramMap[$key] = isset($requestedRoute[$key]) ? $requestedRoute[$key] : null;
                $_GET[preg_replace('/[^A-Za-z0-9\-]/', '', $route_part)] = isset($requestedRoute[$key]) ? $requestedRoute[$key] : null;;
            }
        }

        // if uri query set
        $last_is_query = preg_match('/\?/',end($requestedRoute));

        if($last_is_query) {
            $this->paramMap[] = end($requestedRoute);
        }

        // remove end '/' if set
        if(end($requestedRoute) === '') {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 0, -1);
        }

        $this->routeConfiguration->route = (implode('/',$this->paramMap));
    }

    private function run()
    {
        if ($this->routeConfiguration->route == urldecode($_SERVER['REQUEST_URI'])) {

            if ($this->middleware()) {
                $this->render();
            }
            // if middleware not passed return 404
            (new Response())->setStatusCode(404)->setMessage('resource not found')->send();
        }
    }
    private function middleware(): bool
    {
        // decide where from to execute middleware(bind in app array by name, or concrete path)
        if(empty($this->routeConfiguration->middleware)) return true;
        $middlewareArray = explode(',', $this->routeConfiguration->middleware);

        foreach($middlewareArray as $name) {
            if ( !empty($this->middlewareAllowed[0][$name])) {
                return $this->middlewareAllowed[0][$name]->process();
            }
        }

        return false;
    }

    private function render()
    {
        $ClassName = 'app\Http\Controllers\\'. $this->routeConfiguration->controller;
        $action = $this->routeConfiguration->action;
        (new $ClassName)->$action(...$this->paramRequestMap);
        die();
    }
}


