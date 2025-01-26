<?php

namespace vendor\Request;

class Request
{
    private $method;
    private $uri;
    private $headers;
    private $queryParams;
    private $bodyParams;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->headers = $this->getAllHeaders();
        $this->queryParams = $_GET;
        $this->bodyParams = $this->getBodyParams();
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHeader($name)
    {
        return $this->headers[$name] ?? null;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function getQueryParam($name)
    {
        return $this->queryParams[$name] ?? null;
    }

    public function getBodyParams()
    {
        if ($this->bodyParams === null) {
            $this->bodyParams = [];
            if ($this->method === 'POST' || $this->method === 'PUT' || $this->method === 'PATCH') {
                $contentType = $this->getHeader('Content-Type') ?? '';
                if (strpos($contentType, 'application/json') !== false) {
                    $this->bodyParams = json_decode(file_get_contents('php://input'), true);
                } else {
                    $this->bodyParams = $_POST;
                }
            }
        }
        return $this->bodyParams;
    }

    public function getBodyParam($name)
    {
        return $this->bodyParams[$name] ?? null;
    }

    private function getAllHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}