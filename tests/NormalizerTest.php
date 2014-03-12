<?php

namespace Stidges\Tests;

use Stidges\LaravelDbNormalizer\Entity;
use Stidges\LaravelDbNormalizer\Collection;
use Stidges\LaravelDbNormalizer\Normalizer;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    protected $normalizer;

    public function setUp()
    {
        $this->normalizer = new Normalizer;
    }

    /** @test */
    public function it_converts_an_assoc_array_to_an_entity()
    {
        $data = [ 'foo' => 'bar' ];

        $result = $this->normalizer->normalize($data);

        $this->assertTrue($result instanceof Entity);
        $this->assertEquals('bar', $result->foo);
    }

    /** @test */
    public function it_converts_an_multidimensional_array_to_an_collection()
    {
        $data = [ [ 'foo' => 'bar' ] ];

        $result = $this->normalizer->normalize($data);

        $this->assertTrue($result instanceof Collection);
        $this->assertTrue($result->first() instanceof Entity);
        $this->assertEquals('bar', $result->first()->foo);
    }

    /** @test */
    public function it_converts_nested_arrays_to_entities()
    {
        $data = [ 'foo' => 'bar', 'baz' => [ 'foo' => 'bar' ] ];

        $result = $this->normalizer->normalize($data);

        $this->assertTrue($result->baz instanceof Entity);
    }

    /** @test */
    public function it_converts_nested_multidimensional_arrays_to_collections()
    {
        $data = array('foo' => 'bar', 'baz' => array(array('foo' => 'bar')));

        $result = $this->normalizer->normalize($data);

        $this->assertTrue($result->baz instanceof Collection);
    }
}
