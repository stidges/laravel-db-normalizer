<?php

namespace Stidges\Tests;

require_once __DIR__.'/DbTestCase.php';

use Mockery as m;

class ConnectionTest extends DbTestCase
{
    protected $normalizer;

    public function setUp()
    {
        parent::setUp();

        $this->normalizer = m::mock('Stidges\LaravelDbNormalizer\NormalizerInterface');

        $this->connection->setNormalizer($this->normalizer);
    }

    public function tearDown()
    {
        m::close();

        parent::tearDown();
    }

    /** @test */
    public function it_normalizes_single_results_when_there_are_results()
    {
        $this->normalizer->shouldReceive('normalize')
                         ->once()->with($this->getData()[0])
                         ->andReturn('foo');

        $result = $this->connection->selectOne(
            'SELECT * FROM `table` WHERE `id` = ?', [ 1 ]
        );

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_returns_null_when_there_are_no_single_results()
    {
        $this->normalizer->shouldReceive('normalize')->never();

        $result = $this->connection->selectOne(
            'SELECT * FROM `table` WHERE `id` = ?', [ 'doesnt exist' ]
        );

        $this->assertEquals(null, $result);
    }

    /** @test */
    public function it_returns_a_single_result_when_there_are_many_results()
    {
        $this->normalizer->shouldReceive('normalize')
                         ->once()->with($this->getData()[0])
                         ->andReturn('foo');

        $result = $this->connection->selectOne('SELECT * FROM `table`');

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_normalizes_all_results()
    {
        $this->normalizer->shouldReceive('normalize')
                         ->once()->with($this->getData())
                         ->andReturn('foo');

        $result = $this->connection->select('SELECT * FROM `table`');

        $this->assertEquals('foo', $result);
    }
}
