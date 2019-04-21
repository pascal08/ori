<?php

namespace Pascal\CI;

class Configuration
{

    private $suites;

    public function addSuite(string $name)
    {
        $this->suites[] = $name;
    }

    public function getSuites()
    {
        return $this->suites;
    }
}