<?php

use Phpsafari\Checks\Application\LogLevel;
use Phpsafari\Checks\Application\MaxRatioOf500Responses;
use Phpsafari\Checks\Application\MaxResponseTimeAvg;
use Phpsafari\Checks\Database\DatabaseOnline;
use Phpsafari\Checks\Database\DatabaseUpToDate;
use Phpsafari\Checks\Environment\CorrectEnvironment;
use Phpsafari\Checks\Environment\DebugModeOff;
use Phpsafari\Checks\Filesystem\PathIsWritable;
use Phpsafari\Checks\Queue\QueueIsProcessing;

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
