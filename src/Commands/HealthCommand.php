<?php

namespace Vistik\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Vistik\Exceptions\FailedHealthCheckException;
use Vistik\HealthChecker;
use Vistik\Utils\CheckList;

class HealthCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'health:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run health checks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $checks = config('health.checks');
        $checker = new HealthChecker(new CheckList($checks));

        $output = $checker->prettyPrint();

        $rows = [];
        foreach ($output as $o) {
            $rows[] = [$o['check'], $o['passed'] ? '<comment>passed</comment>' : 'failed', $o['log'], $o['error']];
        }
        $this->table(['check', 'status', 'log', 'error'], $rows);

        // Filter out passed check
        $failingChecks = collect($output)->reject(function($item){
            return $item['passed'] === true;
        });

        // If there is any failing checks throw exception
        if (count($failingChecks) > 0){
            $failingChecks = $failingChecks->implode('check', ', ');

            throw new FailedHealthCheckException("Failed health checks: " . $failingChecks);
        }
    }
}
