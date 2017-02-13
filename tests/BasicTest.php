<?php

use Orchestra\Testbench\TestCase;
use Vistik\Checks\DatabaseOnlineCheck;
use Vistik\Checks\DebugModeOffCheck;
use Vistik\Checks\CorrectEnvironmentCheck;
use Vistik\Checks\QueueCheck;

class BasicTest extends TestCase
{
    /** 
     * @test
     * @group checks
     *  
     */
    public function can_check_for_connection_to_database()
    {
        // Given
        $this->app['config']->set('database.default', 'testbench');
        $this->app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $check = new DatabaseOnlineCheck();

        // When
        $outcome = $check->run();

        // Then
        $this->assertTrue($outcome);
    }

    /**
     * @test
     * @group checks
     *
     */
    public function can_detect_if_connection_to_database_is_broken()
    {
        // Given
        $this->app['config']->set('database.default', 'default'); // Connection does not exist

        $check = new DatabaseOnlineCheck();

        // When
        $outcome = $check->run();

        // Then
        $this->assertFalse($outcome);
    }

    /**
     * @test
     * @group checks
     *
     */
    public function can_detect_if_debug_mode_is_true()
    {
        // Given
        $this->app['config']->set('app.debug', true);
        $check = new DebugModeOffCheck();

        // When
        $outcome = $check->run();

        // Then
        $this->assertFalse($outcome);
    }

    /**
     * @test
     * @group checks
     *
     */
    public function can_detect_if_environment_is_correct()
    {
        // Given
        $this->app['config']->set('app.env', 'production');
        $check = new CorrectEnvironmentCheck();

        // When
        $outcome = $check->run();

        // Then
        $this->assertTrue($outcome);
    }

    /**
     * @test
     * @group checks
     *
     */
    public function can_detect_if_environment_is_wrong()
    {
        // Given
        $this->app['config']->set('app.env', 'testing');
        $check = new CorrectEnvironmentCheck();

        // When
        $outcome = $check->run();

        // Then
        $this->assertFalse($outcome);
    }
    
    /** 
     * @test
     * @group checks
     *  
     */
    public function can_check_queue_system()
    {
        $check = new QueueCheck();

        // When
        $outcome = $check->run();

        // Then
        $this->assertTrue($outcome);
    }

    /**
     * @test
     * @group checks
     *
     */
    public function can_detect_queue_not_processed()
    {
        // Will trigger an SQL error since database tables does not exist
        $this->app['config']->set('queue.default', 'database');
        $check = new QueueCheck();

        // When
        $outcome = $check->run();

        // Then
        $this->assertFalse($outcome);

    }
}