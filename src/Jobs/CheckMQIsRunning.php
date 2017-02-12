<?php

namespace Vistik\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class CheckMQIsRunning extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $id;

    public function __construct($id, string $queue = null)
    {
        $this->id = $id;
        if ($queue !== null){
            $this->onQueue($queue);
        }
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
        // This job is just used to check if Iron MQ is running
//        Log::debug('Checking if IronMQ is running! - If you are seeing this message then it is :)');
//        Log::debug('Command id: ' . $this->getId());
        $file = storage_path('logs') . '/' . $this->getId();
//        Log::debug('Trying to write ' . $file . ' file');
        $success = File::put($file, true);
        if (!$success) {
//            Log::debug('Failed!');
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
