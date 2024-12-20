<?php
namespace Mini\Solid;

class IoCContainer {
    private $bindings = [];
    private $singletons = [];

    public function register($abstract, $concrete, $lifetime = 'transient') {
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'lifetime' => $lifetime,
        ];
    }

    public function resolve($abstract) {
        if (!isset($this->bindings[$abstract])) {
            throw new \Exception("Service not registered: {$abstract}");
        }

        $binding = $this->bindings[$abstract];
        $lifetime = $binding['lifetime'];

        if ($lifetime === 'singleton') {
            if (!isset($this->singletons[$abstract])) {
                $this->singletons[$abstract] = $this->build($binding['concrete']);
            }
            return $this->singletons[$abstract];
        }

        return $this->build($binding['concrete']);
    }

    private function build($concrete) {
        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }

        $reflector = new \ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Cannot instantiate {$concrete}");
        }

        $constructor = $reflector->getConstructor();
        if (!$constructor) {
            return new $concrete;
        }

        $dependencies = array_map(function ($param) {
            $type = $param->getType();
            if (!$type || $type->isBuiltin()) {
                throw new \Exception("Cannot resolve parameter: {$param->name}");
            }
            return $this->resolve($type->getName());
        }, $constructor->getParameters());

        return $reflector->newInstanceArgs($dependencies);
    }
}
