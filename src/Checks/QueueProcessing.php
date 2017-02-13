<?php

namespace Vistik\Checks;

use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Vistik\Jobs\CheckQueueIsRunning;

class QueueProcessing extends HealthCheck
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
            $this->log('Check if queue is getting processed on queue: ' . $cmd->getQueue());
            $this->dispatch($cmd);
        } catch (Exception $e) {
            $this->log($e->getMessage());
            $this->setError($e->getMessage());

            return false;
        }

        $file = $path . $id;
        $max = 10;
        for ($i = 1; $i <= $max; $i++) {
            $this->log(sprintf('Waiting for message from MQ %s/%s', $i, $max));
            try {
                File::get($file);
                File::delete($file);

                $this->log("Got a response");

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
