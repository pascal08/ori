<?php

namespace Pascal\CI;

interface ConfigurationLoaderInterface
{

    /**
     * @param string|null $filePath
     *
     * @return \Pascal\CI\Configuration
     */
    public function load(string $filePath = null): Configuration;
}
