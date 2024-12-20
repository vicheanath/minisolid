<?php
namespace Mini\Solid;

class MiddlewarePipeline {
    private $middleware = [];

    public function add(callable $middleware) {
        $this->middleware[] = $middleware;
    }

    public function handle($request, $response) {
        $pipeline = array_reduce(
            array_reverse($this->middleware),
            fn($next, $middleware) => fn($req, $res) => $middleware($req, $res, $next),
            fn($req, $res) => $res
        );

        return $pipeline($request, $response);
    }
}
