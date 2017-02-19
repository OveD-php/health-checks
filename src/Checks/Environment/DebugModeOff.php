<?php

namespace Vistik\Checks\Environment;

class DebugModeOff extends CheckSetting
{

    public function __construct()
    {
        parent::__construct('app.debug', false);
    }
}
