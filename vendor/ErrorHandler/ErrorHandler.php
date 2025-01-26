<?php

namespace vendor\ErrorHandler;

use vendor\Response\Response;

class ErrorHandler
{

    static public function getErrorName($error)
    {
        $errors = [
            E_ERROR => 'ERROR',
            E_WARNING => 'WARNING',
            E_PARSE => 'PARSE',
            E_NOTICE => 'NOTICE',
            E_CORE_ERROR => 'CORE_ERROR',
            E_CORE_WARNING => 'CORE_WARNING',
            E_COMPILE_ERROR => 'COMPILE_ERROR',
            E_COMPILE_WARNING => 'COMPILE_WARNING',
            E_USER_ERROR => 'USER_ERROR',
            E_USER_WARNING => 'USER_WARNING',
            E_USER_NOTICE => 'USER_NOTICE',
            E_STRICT => 'STRICT',
            E_RECOVERABLE_ERROR => 'RECOVERABLE_ERROR',
            E_DEPRECATED => 'DEPRECATED',
            E_USER_DEPRECATED => 'USER_DEPRECATED',
        ];
        if (array_key_exists($error, $errors)) {
            return $errors[$error] . " [$error]";
        }

        return $error;

    }

    public function register()
    {
        ini_set('display_errors', 'on');
        error_reporting(E_ALL | E_STRICT);

        set_error_handler([$this, 'errorHandler']);

        set_exception_handler([$this, 'exceptionHandler']);

        register_shutdown_function([$this, 'fatalErrorHandler']);
    }


    public function errorHandler($errno, $errstr, $file, $line)
    {

        $this->showError($errno, $errstr, $file, $line);

        return true;
    }


    public function exceptionHandler(\Throwable $e)
    {

        $this->showError(get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), 500);

        return true;
    }

    public function fatalErrorHandler()
    {

        if ($error = error_get_last() and $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            ob_end_clean();

            $this->showError($error['type'], $error['message'], $error['file'], $error['line'], 500);
        }

    }

    public function showError($errno, $errstr, $file, $line, $status = 500)
    {
        $response = new Response();
        header("HTTP/1.1 $status");
        if (ERROR_HANDLER) {
            $message = ' ' . self::getErrorName($errno) . ' ' . $errstr . ' file: ' . $file . '  line: ' . $line;
            $response->setMessage($message);
            $response->setStatusCode($status);
            $response->send();
        } else {
            $message =  $status . ' Server error';
            $response->setMessage($message);
            $response->setStatusCode($status);
            $response->send();

            $message = '**start**' . PHP_EOL . self::getErrorName($errno) . $errstr . ' file: ' . $file . ' line: ' . $line . PHP_EOL . '**end**';
            error_log($message . PHP_EOL, 3, ERROR_LOG_PATH. 'db_error.log');
        }

    }

}

