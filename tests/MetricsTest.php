<?php


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\Mock;
use Orchestra\Testbench\TestCase;
use Vistik\Metrics\Metrics;

class MetricsTest extends TestCase
{
    /**
     * @test
     * @group stats
     *
     */
    public function can_get_ratio()
    {
        // Given
        $mock200 = Mockery::mock(Response::class);
        $mock200->shouldReceive('getStatusCode')->andReturn(200);

        $this->assertEquals(0, Metrics::getRatio(200));

        $mock500 = Mockery::mock(Response::class);
        $mock500->shouldReceive('getStatusCode')->andReturn(500);
        Metrics::trackResponse($mock500);

        // When
        for ($i = 0; $i <= 10; $i++) {
            Metrics::trackResponse($mock200);
        }

        // Then
        $this->assertEquals(91.67, Metrics::getRatio(200));
    }

    /**
     * @test
     * @group stats
     *
     */
    public function can_count_total_and_by_status_code()
    {
        // Given
        $mock200 = Mockery::mock(Response::class);
        $mock200->shouldReceive('getStatusCode')->andReturn(200);

        $this->assertEquals(0, Metrics::getRatio(200));
        $this->assertEquals(0, Metrics::getTotalCount());

        $mock500 = Mockery::mock(Response::class);
        $mock500->shouldReceive('getStatusCode')->andReturn(500);
        Metrics::trackResponse($mock500);

        // When
        for ($i = 0; $i < 10; $i++) {
            Metrics::trackResponse($mock200);
        }

        // Then
        $this->assertEquals(11, Metrics::getTotalCount());
        $this->assertEquals(10, Metrics::getCount(200));
        $this->assertEquals(1, Metrics::getCount(500));
    }

    /**
     * @test
     * @group metrics
     *
     */
    public function can_track_avg_response_time()
    {
        // Given
        $mock200 = Mockery::mock(Response::class);
        $mock200->shouldReceive('getStatusCode')->andReturn(200);
        $mock = Mockery::mock(Request::class);

        // When
        Metrics::trackResponse($mock200);
        Metrics::trackRequest($mock, 100);
        Metrics::trackResponse($mock200);
        Metrics::trackRequest($mock, 50);
        Metrics::trackResponse($mock200);
        Metrics::trackRequest($mock, 750);

        // Then
        $this->assertEquals(300, Metrics::getResponseTimeAvg());
    }
}