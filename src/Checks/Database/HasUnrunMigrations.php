<?php

namespace Vistik\Checks\Database;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Vistik\Checks\HealthCheck;

class HasUnrunMigrations extends HealthCheck
{

    public function run(): bool
    {
        Artisan::call('migrate:status');
        $output = Artisan::output();

        if (Str::contains(trim($output), 'No migrations found')) {
            $this->setError('No migration was found - failing check');

            return false;
        }

        $output = collect(explode("\n", $output));

        $output = $output->filter(function ($item) {
            return Str::contains($item, '| N    | ');
        });

        $output->each(function ($item) {
            $this->setError($item);
        });

        $success = $output->count() == 0;

        if (!$success) {
            $output->each(function ($item) {
                $this->log('Unrun: ' . $item);
            });
            $this->setError('App has unrun migrations');
        } else {
            $this->log('Migrations are up-to-date');
        }

        return $success;
    }
}
