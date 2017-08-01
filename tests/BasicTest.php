<?php

use Orchestra\Testbench\TestCase;
use Phpsafari\Checks\Database\DatabaseOnline;
use Phpsafari\Checks\Database\DatabaseUpToDate;
use Phpsafari\Checks\Environment\CheckConfigSetting;
use Phpsafari\Checks\Environment\CheckEnvironmentSetting;
use Phpsafari\Checks\Environment\CorrectEnvironment;
use Phpsafari\Checks\Environment\DebugModeOff;
use Phpsafari\Checks\Filesystem\PathIsWritable;
use Phpsafari\Checks\Queue\QueueIsProcessing;

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
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $check = new DatabaseOnline();

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

        $check = new DatabaseOnline();

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
        $check = new DebugModeOff();

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
        $check = new CorrectEnvironment();

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
        $check = new CorrectEnvironment();

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
        $check = new QueueIsProcessing();

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
        $check = new QueueIsProcessing();

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
    public function can_check_if_path_does_not_exist()
    {
        // Given
        $check = new PathIsWritable('/path/that/does/not/exist');

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
    public function can_check_if_path_is_writable()
    {
        // Given
        $check = new PathIsWritable('.');

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
    public function can_check_if_app_has_unrun_migrations()
    {
        // Given
        $this->app['config']->set('database.default', 'testbench');
        $this->app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $check = new DatabaseUpToDate();

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
    public function can_check_if_config_is_correct()
    {
        // Given
        $this->app['config']->set('database.default', 'testbench');

        $check = new CheckConfigSetting('database.default', 'testbench');

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
    public function will_fail_if_config_setting_does_not_match()
    {
        // Given
        $this->app['config']->set('database.default', 'testbench');

        $check = new CheckConfigSetting('database.default', 'not-correct');

        // When
        $outcome = $check->run();

        // Then
        $this->assertFalse($outcome);
        $this->assertEquals("Expected value <comment>not-correct</comment> does not match actual value: <comment>testbench</comment>", $check->getError());
    }

    /**
     * @test
     * @group checks
     *
     */
    public function will_fail_if_env_setting_does_not_match()
    {
        // Given
        putenv("visti=testbench");

        $check = new CheckEnvironmentSetting('visti', 'not-correct');

        // When
        $outcome = $check->run();

        // Then
        $this->assertFalse($outcome);
        $this->assertEquals("Expected value <comment>not-correct</comment> does not match actual value: <comment>testbench</comment>", $check->getError());

    }

    /**
     * @test
     * @group checks
     *
     */
    public function can_check_if_env_setting_does_match()
    {
        // Given
        putenv("visti=hey");

        $check = new CheckEnvironmentSetting('visti', 'hey');

        // When
        $outcome = $check->run();

        // Then
        $this->assertTrue($outcome);
    }
}