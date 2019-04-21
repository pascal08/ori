<?php

use Pascal\CI\XmlConfigurationLoader;
use PHPUnit\Framework\TestCase;

class XmlConfigurationLoaderTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_read_configuration_from_xml()
    {
        $xmlConfigurationLoader = new XmlConfigurationLoader();

        $configuration = $xmlConfigurationLoader->load(__DIR__ . '/samples/simple-xml-configuration.xml');

        $suites = $configuration->getSuites();
        $this->assertCount(2, $suites);
        $this->assertTrue(in_array('application', $suites));
        $this->assertTrue(in_array('packages', $suites));
    }
}