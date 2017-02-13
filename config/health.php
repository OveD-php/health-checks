<?php

use Vistik\Checks\CorrectEnvironment;
use Vistik\Checks\DatabaseOnline;
use Vistik\Checks\DebugModeOff;
use Vistik\Checks\QueueProcessing;

return [
    'checks' => [
        new DatabaseOnline(),
        new DebugModeOff(),
        new CorrectEnvironment('production'),
        new QueueProcessing()
    ],
    'route'  => [
        'enabled' => true,
    ]
];
