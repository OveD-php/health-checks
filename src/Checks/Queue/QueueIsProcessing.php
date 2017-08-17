<?php

namespace PhpSafari\Checks\Queue;

use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpSafari\Checks\HealthCheck;
use PhpSafari\Jobs\CheckQueueIsRunning;
use PhpSafari\Utils\Printer;

class QueueIsProcessing extends HealthCheck
{
    use DispatchesJobs;

    /**
     * @var string
     */
    private $queue;

    public function __construct(string $queue = null)
    {
        $this->queue = $queue;
    }

    public function run(): bool
    {
        $id = Str::random();
        $path = './';

        try {
            $cmd = new CheckQueueIsRunning($id, $path, $this->queue);
            $this->log('Check if queue is getting processed - queue: ' . Printer::toString($cmd->getQueue()));
            $this->dispatch($cmd);
        } catch (Exception $e) {
            $this->log($e->getMessage());
            $this->setError($e->getMessage());

            return false;
        }

        $file = $path . $id;
        $max = 10;
        for ($i = 1; $i <= $max; $i++) {
            try {
                File::get($file);
                File::delete($file);

                return true;
            } catch (Exception $e) {
            }
            sleep(1);
        }
        File::delete($file);

        $this->setError(sprintf('Queue health check timeout out after %s secs!', $max));

        return false;
    }
}
