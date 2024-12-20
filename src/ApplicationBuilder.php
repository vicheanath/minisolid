<?php 
namespace Mini\Solid;

class ApplicationBuilder {
    private $container;
    private $pipeline;
    private $router;

    public function __construct() {
        $this->container = new IoCContainer();
        $this->pipeline = new MiddlewarePipeline();
        $this->router = new Router();
    }

    public function services(callable $config) {
        $config($this->container);
        return $this;
    }

    public function middleware(callable $config) {
        $config($this->pipeline);
        return $this;
    }

    public function routes(callable $config) {
        $config($this->router);
        return $this;
    }

    public function run() {
        $request = new Request();
        $response = new Response();

        $this->pipeline->handle($request, $response);
        $this->router->dispatch($request);
    }
}
