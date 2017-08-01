<?php

use Orchestra\Testbench\TestCase;
use Phpsafari\Checks\Application\LogLevel;

class LogLevelTest extends TestCase
{

    /**
     * @test
     * @group check
     *
     */
    public function outcome_is_false_if_log_level_is_wrong()
    {
        // Given
        $this->app['config']->set('app.log_level', 'debug');

        // When
        $check = new LogLevel('error');

        // Then
        $this->assertFalse($check->run());
    }

    /**
     * @test
     * @group check
     *
     */
    public function outcome_is_true_if_log_level_is_correct()
    {
        // Given
        $this->app['config']->set('app.log_level', 'debug');

        // When
        $check = new LogLevel('debug');

        // Then
        $this->assertTrue($check->run());
    }
}