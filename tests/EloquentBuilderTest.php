<?php

namespace Stidges\Tests;

require_once __DIR__.'/DbTestCase.php';

use Mockery as m;
use Stidges\LaravelDbNormalizer\NormalizableModel;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Stidges\LaravelDbNormalizer\Database\EloquentBuilder;

class EloquentBuilderTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $grammar = $this->connection->getQueryGrammar();
        $processor = $this->connection->getPostProcessor();
        $query = new QueryBuilder($this->connection, $grammar, $processor);

        $this->normalizer = m::mock('Stidges\LaravelDbNormalizer\NormalizerInterface');

        $this->builder = new EloquentBuilder($query);
        $this->builder->setNormalizer($this->normalizer);
    }

    public function tearDown()
    {
        m::close();

        parent::tearDown();
    }

    /** @test */
    public function it_normalizes_results()
    {
        $model = new NormalizableModelWithTableStub;

        $this->normalizer->shouldReceive('normalize')
                         ->once()->with($this->getData())
                         ->andReturn('foo');

        $result = $this->builder->setModel($model)->get();

        $this->assertEquals('foo', $result);
    }
}

class NormalizableModelWithTableStub extends NormalizableModel
{
    protected $table = 'table';
}
