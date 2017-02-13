<?php

use Illuminate\Support\Facades\Route;
use Vistik\HealthChecker;
use Vistik\Utils\CheckList;

Route::get(
    '_health',
    function () {
        if (!config('health.route.enabled', false)){
            return response('Route not found', 404);
        }

        $checks = config('health.checks');
        $checker = new HealthChecker(new CheckList($checks));

        $outcome = $checker->getOutcome();

        if (!$outcome) {
            return response()->json(['health' => 'failed'], 500);
        }

        return response()->json(['health' => 'ok'], 200);
    }
);
