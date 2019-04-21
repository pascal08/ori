<?php

namespace Pascal\CI;

class Command
{

    /**
     * @var \Pascal\CI\ConfigurationLoaderInterface
     */
    private $configurationParser;

    /**
     * @param \Pascal\CI\ConfigurationLoaderInterface $configurationParser
     */
    public function __construct(
        ConfigurationLoaderInterface $configurationParser
    ) {
        $this->configurationParser = $configurationParser;
    }

    /**
     * @param bool $exit
     *
     * @return int
     */
    public static function main(bool $exit = true): int
    {
        $command = new static(
            new XmlConfigurationLoader() //ToDo: make extensible
        );

        return $command->run($_SERVER['argv'], $exit);
    }

    /**
     * @param array $argv
     * @param bool  $exit
     *
     * @return int
     */
    public function run(array $argv, bool $exit = true): int
    {
        $this->configurationParser->load();

        $return = 0;

        if ($exit) {
            exit($return);
        }

        return $return;
    }
}
