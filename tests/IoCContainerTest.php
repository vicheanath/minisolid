<?php 
use PHPUnit\Framework\TestCase;
use Mini\Solid\IoCContainer;

class IoCContainerTest extends TestCase {
    public function testSingletonResolution() {
        $container = new IoCContainer();
        $container->register('example', function () {
            return new \stdClass();
        }, 'singleton');

        $instance1 = $container->resolve('example');
        $instance2 = $container->resolve('example');

        $this->assertSame($instance1, $instance2);
    }

    public function testTransientResolution() {
        $container = new IoCContainer();
        $container->register('example', function () {
            return new \stdClass();
        });

        $instance1 = $container->resolve('example');
        $instance2 = $container->resolve('example');

        $this->assertNotSame($instance1, $instance2);
        }

    public function testClassResolution() {
        $container = new IoCContainer();
        $container->register('example', Example::class);

        $instance = $container->resolve('example');

        $this->assertInstanceOf(Example::class, $instance);

    }


    public function testInterfaceResolution() {
        $container = new IoCContainer();
        $container->register( ExampleInterface::class, ExampleImplementation::class);

        $instance = $container->resolve(ExampleInterface::class);

        $this->assertInstanceOf(ExampleImplementation::class, $instance);
    }

    public function testUnregisteredService() {
        $container = new IoCContainer();

        $this->expectException(\Exception::class);
        $container->resolve('example');
    }

    public function testNonInstantiableService() {
        $container = new IoCContainer();
        $container->register('example', 'NonExistentClass');

        $this->expectException(\Exception::class);

        $container->resolve('example');

    }

    public function testDependencyInjection() {
        $container = new IoCContainer();
        $container->register(ExampleInterface::class, ExampleImplementation::class);
        $container->register('test', TestDependency::class);

        $instance = $container->resolve('test');

        $this->assertInstanceOf(TestDependency::class, $instance);
        $this->assertInstanceOf(ExampleImplementation::class, $instance->example);
    }
}



class Example {
    public function __construct() {
    }
}

class Example2 {
    public function __construct(Example $example) {
    }
}

interface ExampleInterface {
    public function exampleMethod();
}

class ExampleImplementation implements ExampleInterface {
    public function exampleMethod() {
        return 'Hello, World!';
    }
}

class ExampleImplementation2 implements ExampleInterface {
    public function exampleMethod() {
        return 'Goodbye, World!';
    }
}


class TestDependency {
    public $example;
    public function __construct(ExampleInterface $example) {
        $this->example = $example;
    }
}