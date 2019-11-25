<?php

use Orchestra\Testbench\TestCase;
use PhpSafari\Checks\Environment\CorrectEnvironment;
use PhpSafari\Checks\Database\DatabaseOnline;
use PhpSafari\Checks\Environment\DebugModeOff;
use PhpSafari\Exceptions\NoHealthChecksSetupException;
use PhpSafari\HealthChecker;
use PhpSafari\Utils\CheckList;

class HealthCheckerTest extends TestCase
{

    /**
     * @test
     * @group checks
     *
     */
    public function can_run_multiple_checks()
    {
        // Given
        $this->app['config']->set('database.default', 'testbench');
        $this->app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $this->app['config']->set('app.env', 'production');

        $checkList = new CheckList([new DatabaseOnline(), new DebugModeOff(), new CorrectEnvironment()]);
        $checker = new HealthChecker($checkList);

        // When
        $outcome = $checker->getOutcome();

        // Then
        $this->assertTrue($outcome);
    }

    /**
     * @test
     * @group checks
     */
    public function throws_execption_if_no_health_checks_is_setup()
    {
        // Given
        $checkList = new CheckList();
        $checker = new HealthChecker($checkList);

        // Assert
        $this->expectException(NoHealthChecksSetupException::class);
        $this->expectExceptionMessage('No health check is setup!');

        // When
        $checker->run();

        // Then

    }
}
