<?php
class Router {
    private $routes = [];

    public function registerRoutes($controller) {
        $reflector = new ReflectionClass($controller);
        $methods = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $docComment = $method->getDocComment();

            if (preg_match('/@Route\("(?P<uri>.*?)", method="(?P<method>GET|POST|PUT|DELETE)"\)/', $docComment, $matches)) {
                $this->routes[] = [
                    'uri' => $matches['uri'],
                    'method' => $matches['method'],
                    'action' => [$controller, $method->getName()],
                ];
            }
        }
    }

    public function dispatch(Request $request) {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $request->uri && strtoupper($route['method']) === strtoupper($request->method)) {
                return call_user_func($route['action'], $request);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}