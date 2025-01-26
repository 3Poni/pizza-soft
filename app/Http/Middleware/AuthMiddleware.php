<?php

namespace app\Http\Middleware;

use vendor\App\Contracts\MiddlewareInterface;
use vendor\Request\Request;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(): bool
    {
        $request = new Request();
        if(AUTH_KEY !== $request->getHeader('X-Auth-Key')) {
            return false;
        }
        return true;
    }
}