<?php

use Pascal\Router\Action;
use Pascal\Router\ActionFactory;
use Pascal\Router\NoRouteFoundException;
use Pascal\Router\Route;
use Pascal\Router\RouteArguments;
use Pascal\Router\RouteFactory;
use Pascal\Router\RouteParser;
use Pascal\Router\Router;
use Pascal\Router\Testing\InvokableClass;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    /** @test */
    public function it_should_route_a_request_based_on_uri_and_http_method()
    {
        $router = $this->buildRouter();
        $router->bind($method = 'GET', $uri = '/', function () {
            return 'Hello World!';
        });

        $output = $this->runRouter($router, $method, $uri);

        $this->assertEquals('Hello World!', $output);
    }

    /** @test */
    public function it_should_throw_an_exception_when_request_does_not_match_a_registered_route()
    {
        $this->expectException(NoRouteFoundException::class);

        $routeParser = $this->createMock(RouteParser::class);
        $routeParser
            ->method('isMatching')
            ->willReturn(false);
        $router = $this->buildRouter($routeParser);
        $router->bind($method = 'GET', $uri = '/', function () {
            return 'Hello World!';
        });

        $output = $this->runRouter($router, $method, '/some-unbound-uri');

        $this->assertEquals('', $output);
    }

    /** @test */
    public function it_should_bind_a_closure()
    {
        $router = $this->buildRouter();
        $router->bind($method = 'GET', $uri = '/', function () {
            return 'Hello World!';
        });

        $output = $this->runRouter($router, $method, $uri);

        $this->assertEquals('Hello World!', $output);
    }

    /** @test */
    public function it_should_bind_a_class_by_invoke()
    {
        $router = $this->buildRouter();
        $router->bind($method = 'GET', $uri = '/', new InvokableClass);

        $output = $this->runRouter($router, $method, $uri);

        $this->assertEquals('Calling from an invokable class.', $output);
    }

    /** @test */
    public function it_should_bind_a_static_class_method()
    {
        $router = $this->buildRouter();
        $router->bind($method = 'GET', $uri = '/', [\Pascal\Router\Testing\StaticMethodInClass::class, 'home']);

        $output = $this->runRouter($router, $method, $uri);

        $this->assertEquals('Calling from a static class method.', $output);
    }

    /** @test */
    public function it_should_route_with_variable_uri_parts()
    {
        $routeParserMock = $this->createMock(RouteParser::class);
        $routeParserMock
            ->method('isMatching')
            ->willReturn(true);
        $routeParserMock
            ->method('parseArguments')
            ->willReturn(new RouteArguments([42, 1337]));
        $router = $this->buildRouter($routeParserMock);
        $router->bind($method = 'GET', '/organization/{organizationId}/user/{userId}', function ($organizationId, $userId) {
            return 'Organization: ' . $organizationId . ', User: ' . $userId . '.';
        });

        $output = $this->runRouter($router, $method, '/organization/42/user/1337');

        $this->assertEquals('Organization: 42, User: 1337.', $output);
    }

    /**
     * @param \Pascal\Router\Router $router
     * @param string                $method
     * @param string                $uri
     *
     * @return string
     */
    private function runRouter(Router $router, string $method, string $uri): string
    {
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['REQUEST_METHOD'] = $method;

        return $router->run();
    }

    /**
     * @param null $routeParser
     *
     * @return \Pascal\Router\Router
     */
    private function buildRouter($routeParser = null): \Pascal\Router\Router
    {
        return new Router(
            $this->buildActionFactoryMock(),
            $this->buildRouteFactoryMock(),
            $routeParser ?: $this->buildRouteParserMock()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function buildActionFactoryMock(): \PHPUnit\Framework\MockObject\MockObject
    {
        $actionFactoryMock = $this->createMock(ActionFactory::class);
        $actionFactoryMock
            ->method('fromCallable')
            ->willReturnCallback(function ($callable) {
                return new Action($callable);
            });

        return $actionFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function buildRouteParserMock(): \PHPUnit\Framework\MockObject\MockObject
    {
        $routeParser = $this->createMock(RouteParser::class);
        $routeParser
            ->method('isMatching')
            ->willReturn(true);
        $routeParser
            ->method('parseArguments')
            ->willReturn(new RouteArguments([]));

        return $routeParser;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function buildRouteFactoryMock(): \PHPUnit\Framework\MockObject\MockObject
    {
        $routeFactoryMock = $this->createMock(RouteFactory::class);
        $routeFactoryMock
            ->method('fromString')
            ->willReturnCallback(function ($route) {
                return new Route($route);
            });

        return $routeFactoryMock;
    }
}