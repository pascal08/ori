<?php

namespace Pascal\Router\Tests;

use Pascal\Router\CouldNotParseRouteException;
use Pascal\Router\RouteParameterRegexFilter;
use PHPUnit\Framework\TestCase;

class RouteParameterRegexFilterTest extends TestCase
{

    /** @test */
    public function it_should_filter_route_parameters_from_uri_parts_with_regex_constraint()
    {
        $RouteParameterRegexFilter = new RouteParameterRegexFilter;

        $routeArguments = $RouteParameterRegexFilter->filter([
            '' => '',
            'organization' => 'organization',
            '{organizationId:\d+}' => '42',
            'user' => 'user',
            '{userId:\d+}' => '1337',
        ]);

        $this->assertEquals(['organizationId' => '42', 'userId' => '1337'], $routeArguments);
    }

    /** @test */
    public function it_should_throw_an_exception_regex_constraint_is_voilated()
    {
        $this->expectException(CouldNotParseRouteException::class);

        $RouteParameterRegexFilter = new RouteParameterRegexFilter;

        $RouteParameterRegexFilter->filter([
            '' => '',
            'organization' => 'organization',
            '{organizationId:\d+}' => '42',
            'user' => 'user',
            '{userId:\d+}' => 'john',
        ]);
    }
}
