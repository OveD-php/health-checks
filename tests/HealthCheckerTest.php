<?php

use Orchestra\Testbench\TestCase;
use Vistik\Checks\CanConnectToDatabaseCheck;
use Vistik\Checks\DebugModeCheck;
use Vistik\Checks\EnvironmentCheck;
use Vistik\HealthChecker;
use Vistik\Utils\CheckList;

class HealthCheckerTest extends TestCase
{

    /**
     * @test
     * @group checks
     *
     */
    public function run_multiple_checks()
    {
        // Given
        $this->app['config']->set('database.default', 'testbench');
        $this->app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $this->app['config']->set('app.env', 'production');

        $checkList = new CheckList([new CanConnectToDatabaseCheck(), new DebugModeCheck(), new EnvironmentCheck()]);
        $checker = new HealthChecker($checkList);

        // When
        $outcome = $checker->run();

        // Then
        $this->assertTrue($outcome);
    }
}