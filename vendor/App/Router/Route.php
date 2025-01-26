<?php

namespace vendor\App\Router;

class Route
{
    private static array $routesGet = [];
    private static array $routesPost = [];
    private static ?array $routesMiddleware = [];

    public static function middleware(): array
    {
        return self::$routesMiddleware;
    }

    public static function getRoutesGet(): array
    {
        return self::$routesGet;
    }

    public static function getRoutesPost(): array
    {
        return self::$routesPost;
    }

    public static function get(string $route, string $controller): RouteConfiguration
    {
        $controller = explode('@', $controller);
        $routeConfiguration = new RouteConfiguration($route, $controller[0], $controller[1]);
        self::$routesGet[] = $routeConfiguration;
        return $routeConfiguration;
    }
    public static function post(string $route, string $controller): RouteConfiguration
    {
        $controller = explode('@', $controller);
        $routeConfiguration = new RouteConfiguration($route, $controller[0], $controller[1]);
        self::$routesPost[] = $routeConfiguration;
        return $routeConfiguration;
    }
    public static function redirect($url)
    {
        header('Location: ' . $url);
    }

}


