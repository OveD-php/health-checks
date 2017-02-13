<?php

namespace Vistik;

use Illuminate\Support\ServiceProvider;
use Vistik\Commands\HealthCommand;

class HealthCheckServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/health.php' => config_path('health.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                HealthCommand::class,
            ]);
        }
    }
}
