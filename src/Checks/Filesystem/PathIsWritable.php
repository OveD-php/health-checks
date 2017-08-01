<?php

namespace Phpsafari\Checks\Filesystem;

use Phpsafari\Checks\HealthCheck;

class PathIsWritable extends HealthCheck
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
        $this->log(sprintf('Checking if %s is writeable...', $this->path));

        return is_writeable($this->path) && is_dir($this->path);
    }
}
