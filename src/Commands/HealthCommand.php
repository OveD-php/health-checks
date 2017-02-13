<?php

namespace Vistik\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Timekit\Account;
use Timekit\Filter;
use Timekit\Jobs\DeleteUser as DeleteUserJob;
use Timekit\User;
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

        foreach ($output as $name => $passed) {
            if ($passed) {
                $this->info($name);
                continue;
            }
            $this->error($name);
        }

        $checker->run();
    }
}
