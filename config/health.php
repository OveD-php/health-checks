<?php

use Vistik\Checks\DatabaseOnline;
use Vistik\Checks\DebugModeOff;
use Vistik\Checks\CorrectEnvironmentHealthCheck;
use Vistik\Checks\QueueProcessing;

return [
    'checks' => [
        new DatabaseOnline(),
        new DebugModeOff(),
        new CorrectEnvironmentHealthCheck('production'),
        new QueueProcessing()
    ]
];
