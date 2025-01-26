<?php

namespace vendor\Response;

class Response
{
    private $statusCode;
    private $data;
    private $message;
    private $errors;

    public function __construct($statusCode = 200, $data = null, $message = '', $errors = [])
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->message = $message;
        $this->errors = $errors;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    public function send()
    {
        http_response_code($this->statusCode);

        $response = [
            'status' => $this->statusCode >= 200 && $this->statusCode < 300 ? 'success' : 'error',
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
        ];

        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }

    public static function success($data = null, $message = '')
    {
        $response = new self(200, $data, $message);
        $response->send();
    }

    public static function not_found($message = 'resource not found')
    {
        $response = new self(404, $message);
        $response->send();
    }

    public static function error($statusCode = 400, $message = '', $errors = [])
    {
        $response = new self($statusCode, null, $message, $errors);
        $response->send();
    }
}
