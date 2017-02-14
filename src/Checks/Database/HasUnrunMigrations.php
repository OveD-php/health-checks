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
            $this->log('No migration was found - failing check');

            return false;
        }

        $output = collect(explode("\n", $output));

        $this->log("Not yet migrated:");
        $output->each(function($item){
            $item = str_replace(['| N    | ', ' |'], '', $item);
            $this->log($item);
        });

        return $output->count() == 0;
    }
}
