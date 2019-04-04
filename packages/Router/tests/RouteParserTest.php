<?php

use Pascal\Router\CouldNotParseRouteException;
use Pascal\Router\Route;
use Pascal\Router\RouteArguments;
use Pascal\Router\RouteParameterFilter;
use Pascal\Router\RouteParser;
use PHPUnit\Framework\TestCase;

class RouteParserTest extends TestCase
{

    /** @test */
    public function it_should_return_true_if_a_route_matches_a_uri()
    {
        $routeParameterFilterMock = $this->createMock(RouteParameterFilter::class);
        $routeParameterFilterMock
            ->method('filter')
            ->willReturn([
               'organizationId' => '42',
               'userId' => '1337'
            ]);

        $routeParser = new RouteParser(
            $routeParameterFilterMock
        );

        $result = $routeParser->isMatching(
            new Route('/organization/{organizationId}/user/{userId}'),
            '/organization/42/user/1337'
        );

        $this->assertTrue($result);
    }

    /** @test */
    public function it_should_return_false_if_a_route_does_not_match_a_uri_based_on_path_part_names()
    {
        $routeParameterFilterMock = $this->createMock(RouteParameterFilter::class);
        $routeParameterFilterMock
            ->method('filter')
            ->willReturn([
                'organizationId' => '42',
                'userId' => '1337'
            ]);

        $routeParser = new RouteParser(
            $routeParameterFilterMock
        );

        $result = $routeParser->isMatching(
            new Route('/user/{userId}/item/{itemId}}'),
            '/organization/42/user/1337'
        );

        $this->assertFalse($result);
    }

    /** @test */
    public function it_should_return_false_if_a_route_does_not_match_a_uri_based_on_path_length()
    {
        $routeParameterFilterMock = $this->createMock(RouteParameterFilter::class);
        $routeParameterFilterMock
            ->method('filter')
            ->willReturn([
                'organizationId' => '42',
                'userId' => '1337'
            ]);

        $routeParser = new RouteParser(
            $routeParameterFilterMock
        );

        $result = $routeParser->isMatching(
            new Route('/user/{userId}'),
            '/organization/42/user/1337'
        );

        $this->assertFalse($result);
    }

    /** @test */
    public function it_should_parse_arguments_from_a_route()
    {
        $routeParameterFilterMock = $this->createMock(RouteParameterFilter::class);
        $routeParameterFilterMock
            ->method('filter')
            ->willReturn([
                'organizationId' => '42',
                'userId' => '1337'
            ]);

        $routeParser = new RouteParser(
            $routeParameterFilterMock
        );

        $routeArguments = $routeParser->parseArguments(
            new Route('/organization/{organizationId}/user/{userId}'),
            '/organization/42/user/1337'
        );

        $this->assertEquals(new RouteArguments(['organizationId' => '42', 'userId' => '1337']), $routeArguments);
    }

    /** @test */
    public function it_should_throw_an_exception_if_it_could_not_parse_arguments_from_a_route()
    {
        $this->expectException(CouldNotParseRouteException::class);

        $routeParameterFilterMock = $this->createMock(RouteParameterFilter::class);
        $routeParameterFilterMock
            ->method('filter')
            ->willReturn([
                'organizationId' => '42',
                'userId' => '1337'
            ]);

        $routeParser = new RouteParser(
            $routeParameterFilterMock
        );

        $routeArguments = $routeParser->parseArguments(
            new Route('/organization/{organizationId}'),
            '/organization/42/user/1337'
        );

        $this->assertEquals(new RouteArguments(['organizationId' => '42', 'userId' => '1337']), $routeArguments);
    }

}