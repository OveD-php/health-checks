<?php

namespace Vistik\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class CheckQueueIsRunning extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $id;
    /**
     * @var string
     */
    private $path;

    public function __construct($id, string $path, string $queue = null)
    {
        $this->id = $id;
        if ($queue !== null) {
            $this->onQueue($queue);
        }
        $this->path = $path;
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
        // This job is just used to check if job are getting processed
        $file = $this->path . $this->getId();
        File::put($file, true);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getRawBody()
    {
        
    }
}
