<?php

namespace app;

use app\Http\Middleware\AuthMiddleware;
use vendor\App\App;
use vendor\ErrorHandler\ErrorHandler;

class Kernel
{
    public function __construct()
    {
        require_once "Helpers/helpers.php";
        require_once "../routes/api.php";
        require_once "../config.php";
        require_once "../vendor/Errorhandler/ErrorHandler.php";

        $e = new ErrorHandler;
        $e->register();
    }

    public function run(): void
    {
        $app = new App();
        $app->setMiddleware('auth', new AuthMiddleware());
        $app->run();
    }
}