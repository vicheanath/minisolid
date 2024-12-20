<?php

namespace Mini\Solid;

abstract class ApiController extends Controller {
    /**
     * Send a JSON response.
     *
     * @param mixed $data Data to be returned as JSON
     * @param int $status HTTP status code
     */
    protected function json($data, int $status = 200) {
        Response::json($data, $status);
    }

    /**
     * Send an error response in JSON format.
     *
     * @param string $message Error message
     * @param int $status HTTP status code
     */
    protected function error(string $message, int $status = 400) {
        $this->json(['error' => $message], $status);
    }
}
