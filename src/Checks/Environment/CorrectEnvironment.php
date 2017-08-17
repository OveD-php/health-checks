<?php

namespace PhpSafari\Checks\Environment;

class CorrectEnvironment extends CheckConfigSetting
{

    public function __construct($env = 'production')
    {
        parent::__construct('app.env', $env);
    }
}
