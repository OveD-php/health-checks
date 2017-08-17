<?php

namespace PhpSafari\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use PhpSafari\Commands\HealthCommand;

class HealthCheckServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/health.php' => config_path('health.php'),
        ], 'health');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                HealthCommand::class,
            ]);
        }
    }
}
