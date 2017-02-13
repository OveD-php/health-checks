<?php

use Orchestra\Testbench\TestCase;
use Vistik\Checks\DatabaseOnlineHealthCheck;
use Vistik\Checks\DebugModeOffHealthCheck;
use Vistik\Checks\CorrectEnvironmentHealthCheck;
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

        $checkList = new CheckList([new DatabaseOnlineHealthCheck(), new DebugModeOffHealthCheck(), new CorrectEnvironmentHealthCheck()]);
        $checker = new HealthChecker($checkList);

        // When
        $outcome = $checker->getOutcome();

        // Then
        $this->assertTrue($outcome);
    }

    /**
     * @test
     * @group checks
     * @expectedException Vistik\Exceptions\NoHealthChecksSetupException
     * @expectedExceptionMessage No health check is setup!
     */
    public function throws_execption_if_no_health_checks_is_setup()
    {
        // Given
        $checkList = new CheckList();
        $checker = new HealthChecker($checkList);

        // When
        $checker->run();

        // Then

    }
}