<?php
class Response {
    public static function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public static function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/views/{$view}.php";
    }

    public static function plain($text, $status = 200) {
        http_response_code($status);
        header('Content-Type: text/plain');
        echo $text;
    }
}