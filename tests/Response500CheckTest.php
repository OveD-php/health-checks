<?php

use Illuminate\Http\Response;
use Orchestra\Testbench\TestCase;
use Vistik\Checks\Application\MaxRatioOf500Responses;
use Vistik\Metrics\Metrics;

class Response500CheckTest extends TestCase
{

    /**
     * @test
     * @group checks
     *
     */
    public function health_check()
    {
        // Given
        $check = new MaxRatioOf500Responses(1.00);
        $this->assertTrue($check->run());

        // When
        $mock = Mockery::mock(Response::class);
        $mock->shouldReceive('getStatusCode')->andReturn(500);
        Metrics::trackResponse($mock);

        $mock = Mockery::mock(Response::class);
        $mock->shouldReceive('getStatusCode')->andReturn(200);
        Metrics::trackResponse($mock);

        // Then
        $this->assertFalse($check->run());
        $this->assertEquals(50, Metrics::getRatio(500));
    }
}