<?php
class Request {
    public $method;
    public $uri;
    public $body;
    public $query;
    public $headers;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->body = json_decode(file_get_contents('php://input'), true) ?: [];
        $this->query = $_GET;
        $this->headers = getallheaders();
    }

    public function input($key, $default = null) {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }
}
