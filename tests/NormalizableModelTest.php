<?php

namespace Stidges\Tests;

use Mockery as m;
use Stidges\LaravelDbNormalizer\NormalizableModel;

class NormalizableModelTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_returns_the_correct_builder()
    {
        $builder = m::mock('Illuminate\Database\Query\Builder');

        $model = new NormalizableModelStub;
        $result = $model->newEloquentBuilder($builder);

        $this->assertInstanceOf('Stidges\LaravelDbNormalizer\Database\EloquentBuilder', $result);
    }

    /** @test */
    public function it_creates_a_new_instance_from_a_plain_array()
    {
        $data  = [ 'foo' => 'bar' ];
        $model = new NormalizableModelStub;

        $result = $model->newFromBuilder($data);

        $this->assertEquals('bar', $result->getAttribute('foo'));
    }

    /** @test */
    public function it_creates_a_new_instance_from_an_arrayable_object()
    {
        $arrayable = m::mock('Illuminate\Support\Contracts\ArrayableInterface');
        $arrayable->shouldReceive('toArray')->andReturn([ 'foo' => 'bar' ]);

        $model = new NormalizableModelStub;

        $result = $model->newFromBuilder($arrayable);

        $this->assertEquals('bar', $result->getAttribute('foo'));
    }
}

class NormalizableModelStub extends NormalizableModel
{

}
