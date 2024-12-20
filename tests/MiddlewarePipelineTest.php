<?php

use PHPUnit\Framework\TestCase;

use Mini\Solid\MiddlewarePipeline;

class MiddlewarePipelineTest extends TestCase
{
    public function testMiddlewarePipeline()
    {
        $request = new \stdClass();
        $response = new \stdClass();

        $pipeline = new MiddlewarePipeline();

        $pipeline->add(function ($request, $response, $next) {
            $request->first = true;
            $response->first = true;
            return $next($request, $response);
        });

        $pipeline->add(function ($request, $response, $next) {
            $request->second = true;
            $response->second = true;
            return $next($request, $response);
        });

        $pipeline->add(function ($request, $response, $next) {
            $request->third = true;
            $response->third = true;
            return $next($request, $response);
        });

        $response = $pipeline->handle($request, $response);

        $this->assertTrue($request->first);
        $this->assertTrue($request->second);
        $this->assertTrue($request->third);

        $this->assertTrue($response->first);
        $this->assertTrue($response->second);

        $this->assertTrue($response->third);

    }

    public function testMiddlewarePipelineWithEmptyMiddleware()
    {
        $request = new \stdClass();
        $response = new \stdClass();

        $pipeline = new MiddlewarePipeline();

        $response = $pipeline->handle($request, $response);

        $this->assertEmpty((array) $request);
        $this->assertEmpty((array) $response);
    }


    public function testMiddlewarePipelineWithEmptyMiddlewareAndRequest()
    {
        $request = new \stdClass();
        $response = new \stdClass();

        $pipeline = new MiddlewarePipeline();

        $pipeline->add(function ($request, $response, $next) {
            $request->first = true;
            $response->first = true;
            return $next($request, $response);
        });

        $response = $pipeline->handle($request, $response);

        $this->assertTrue($request->first);

        $this->assertTrue($response->first);


    }


    public function testMiddlewarePipelineWithEmptyMiddlewareAndResponse()
    {
        $request = new \stdClass();
        $response = new \stdClass(); 

        $pipeline = new MiddlewarePipeline();

        $pipeline->add(function ($request, $response, $next) {
            $request->first = true;
            $response->first = true;
            return $next($request, $response);
        });


        $response = $pipeline->handle($request, $response);

        $this->assertTrue($request->first);

        $this->assertTrue($response->first);


    }

}