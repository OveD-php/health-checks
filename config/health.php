<?php

use Vistik\Checks\Application\LogLevel;
use Vistik\Checks\Application\MaxRatioOf500Responses;
use Vistik\Checks\Application\MaxResponseTimeAvg;
use Vistik\Checks\Database\DatabaseOnline;
use Vistik\Checks\Database\DatabaseUpToDate;
use Vistik\Checks\Environment\CorrectEnvironment;
use Vistik\Checks\Environment\DebugModeOff;
use Vistik\Checks\Filesystem\PathIsWritable;
use Vistik\Checks\Queue\QueueIsProcessing;

return [
    'checks' => [
        new DatabaseOnline(),
        new DatabaseUpToDate(),
        new DebugModeOff(),
        new LogLevel('error'),
        new CorrectEnvironment('production'),
        new QueueIsProcessing(),
        new PathIsWritable(storage_path()),
        new PathIsWritable(storage_path('logs')),
        new PathIsWritable(storage_path('framework/sessions')),
        new PathIsWritable(storage_path('framework/cache')),
        new MaxRatioOf500Responses(1.00),
        new MaxResponseTimeAvg(300),
    ],
    'route'  => [
        'enabled' => true,
    ]
];
