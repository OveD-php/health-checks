<?php

namespace Vistik\Checks;

use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Vistik\Jobs\CheckMQIsRunning;

class QueueCheck extends Check
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
        try {
            $cmd = new CheckMQIsRunning($id, $this->queue);
            $this->log('Check if MQ is up and running and using queue: ' . $cmd->getQueue());
            $this->dispatch($cmd);
        } catch (Exception $e) {
            $this->log($e->getMessage());
            $this->setError($e->getMessage());

            return false;
        }

        $file = storage_path('logs') . '/' . $id;
        $max = 10;
        for ($i = 1; $i <= $max; $i++) {
            $this->log(sprintf('Waiting for message from MQ %s/%s', $i, $max));
            try {
                File::get($file);
                File::delete($file);

                $this->log("Get a response");

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