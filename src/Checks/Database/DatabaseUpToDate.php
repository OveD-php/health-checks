<?php

namespace Vistik\Checks\Database;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Vistik\Checks\HealthCheck;

class DatabaseUpToDate extends HealthCheck
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

        $output = $output->reject(function($item){
            return !Str::contains($item, '| N    | ');
        });

        $this->log("Checking if any migrations are not yet applied!");
        $unAppliedMigrations = [];
        $output->each(function ($item) {
            $item = str_replace(['| N    | ', ' |'], '', $item);
            $unAppliedMigrations[] = $item;
        });

        $check = $output->count() == 0;

        if (!$check){
            $this->setError(implode("\n", $unAppliedMigrations));
        }

        return $check;
    }
}
