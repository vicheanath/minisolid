<?php
namespace Mini\Solid;

use Mini\Solid\Request;
use Mini\Solid\Response;

abstract class Controller {
    protected Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Render a view with data.
     *
     * @param string $view Name of the view file
     * @param array $data Data to pass to the view
     */
    protected function view(string $view, array $data = []) {
        Response::view($view, $data);
    }

    /**
     * Redirect to another URL.
     *
     * @param string $url The URL to redirect to
     * @param int $status HTTP status code
     */
    protected function redirect(string $url, int $status = 302) {
        http_response_code($status);
        header("Location: $url");
        exit;
    }
}
