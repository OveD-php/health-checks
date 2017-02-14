<?php

namespace Vistik\Checks\Database;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Vistik\Checks\HealthCheck;

class HasUnrunMigrations extends HealthCheck
{

    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function run(): bool
    {
        Artisan::call('migrate:status', ['--path' => $this->path]);
        $output = Artisan::output();

        if (Str::contains(trim($output), 'No migrations found')) {
            $this->log('No migration was found - failing check');

            return false;
        }

        $output = collect(explode("\n", $output));

        $output = $output->reject(function ($item) {
            return !Str::contains($item, "| N    |");
        });

        return $output->count() > 0;
    }
}
