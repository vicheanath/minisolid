<?php
namespace Mini\Solid;

class IoCContainer {
    private $bindings = [];
    private $singletons = [];

    /**
     * Register a service with the IoC container.
     *
     * @param string $abstract Interface or class name
     * @param string|callable $concrete Implementation or factory
     * @param string $lifetime Lifetime of the service ('singleton' or 'transient')
     */
    public function register($abstract, $concrete, $lifetime = 'transient') {
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'lifetime' => $lifetime,
        ];
    }
    /**
     * Resolve a service from the container.
     *
     * @param string $abstract Interface or class name
     * @return mixed The resolved instance
     * @throws \Exception If the service is not registered
     */
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
    /**
     * Build an instance of the given concrete class or factory.
     *
     * @param string|callable $concrete Implementation or factory
     * @return mixed The built instance
     * @throws \ReflectionException
     */
    private function build($concrete) {
        
        // If the concrete is a closure or factory, invoke it

        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }

        $reflector = new \ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Cannot instantiate {$concrete}");
        }

        // Use Reflection to resolve dependencies
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
