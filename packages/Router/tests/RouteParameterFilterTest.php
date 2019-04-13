<?php

namespace Pascal\Router\Tests;

use Pascal\Router\RouteParameterFilter;
use PHPUnit\Framework\TestCase;

class RouteParameterFilterTest extends TestCase
{

    /** @test */
    public function it_should_filter_route_parameters_from_uri_parts()
    {
        $routeParameterFilter = new RouteParameterFilter;

        $routeArguments = $routeParameterFilter->filter([
            '' => '',
            'organization' => 'organization',
            '{organizationId}' => '42',
            'user' => 'user',
            '{userId}' => '1337',
        ]);

        $this->assertEquals(['organizationId' => '42', 'userId' => '1337'], $routeArguments);
    }
}
