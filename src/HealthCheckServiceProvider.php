<?php

namespace Vistik;

use Illuminate\Support\ServiceProvider;
use Vistik\Commands\HealthCommand;

class HealthCheckServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // registrer health route

        // auth check?

        if ($this->app->runningInConsole()) {
            $this->commands([
                HealthCommand::class,
            ]);
        }
    }
}
