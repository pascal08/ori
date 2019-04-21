<?php

namespace Pascal\CI;

class XmlConfigurationLoader implements ConfigurationLoaderInterface
{

    /**
     * @var \SimpleXMLElement
     */
    private $xml;

    /**
     * @param string|null $filePath
     *
     * @return \Pascal\CI\Configuration
     * @throws \Pascal\CI\CouldNotFindConfigurationException
     */
    public function load(string $filePath = null): Configuration
    {
        $this->loadXML($filePath);

        return $this->parseConfiguration();
    }

    /**
     * @param string|null $filePath
     *
     * @throws \Pascal\CI\CouldNotFindConfigurationException
     */
    private function loadXML(string $filePath = null)
    {
        foreach ([$filePath, 'phpci.xml', 'phpci.xml.dist'] as $file) {
            if (\file_exists($file)) {
                $this->xml = simplexml_load_file(\realpath('phpci.xml'));
                return;
            }
        }

        throw new CouldNotFindConfigurationException(
            'Either phpci.xml or phpci.xml.dist was not found.'
        );
    }

    /**
     * @return \Pascal\CI\Configuration
     */
    private function parseConfiguration(): Configuration
    {
        $configuration = new Configuration;

        foreach ($this->xml->suites->children() as $suite) {
            foreach ($suite->attributes() as $key => $value) {
                if ($key === 'name') {
                    $configuration->addSuite($value);
                }
            }
        }

        return $configuration;
    }
}
