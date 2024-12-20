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
}
