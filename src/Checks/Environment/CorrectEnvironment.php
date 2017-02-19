<?php

namespace Vistik\Checks\Environment;


class CorrectEnvironment extends CheckSetting
{

    public function __construct($env = 'production')
    {
        //s$this->env = $env;
        parent::__construct('app.env', $env);
    }
}
