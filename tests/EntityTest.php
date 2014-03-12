<?php

namespace Stidges\Tests;

use Stidges\LaravelDbNormalizer\Entity;
use Stidges\LaravelDbNormalizer\Collection;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_allows_data_to_be_set()
    {
        $entity = new Entity;

        $entity->set('foo', 'bar');

        $this->assertEquals($entity->get('foo'), 'bar');
    }

    /** @test */
    public function it_returns_a_default_value_when_key_doesnt_exist()
    {
        $entity = new Entity;

        $this->assertEquals($entity->get('foo', 'default'), 'default');
    }

    /** @test */
    public function it_returns_the_value_of_a_closure_when_key_doesnt_exist()
    {
        $entity = new Entity;
        $closure = function()
        {
            return 'default';
        };

        $this->assertEquals($entity->get('foo', $closure), 'default');
    }

    /** @test */
    public function it_can_be_initialized_with_data()
    {
        $entity = new Entity([ 'foo' => 'bar' ]);

        $this->assertEquals($entity->get('foo'), 'bar');
    }

    /** @test */
    public function it_allows_an_array_of_data_to_be_set()
    {
        $entity = new Entity;

        $entity->set([ 'foo' => 'bar' ]);

        $this->assertEquals($entity->get('foo'), 'bar');
    }

    /** @test */
    public function it_keeps_track_of_modified_values()
    {
        $entity = new Entity([ 'foo' => 'bar' ]);

        $resultOne = $entity->getDirtyAttributes();

        $entity->set('foo', 'baz');

        $resultTwo = $entity->getDirtyAttributes();

        $this->assertEquals([], $resultOne);
        $this->assertEquals([ 'foo' => 'baz' ], $resultTwo);
    }

    /** @test */
    public function it_allows_all_attributes_to_be_retrieved()
    {
        $data = [ 'foo' => 'bar', 'baz' => 'foo' ];

        $entity = new Entity($data);

        $this->assertEquals($data, $entity->getAttributes());
    }

    /** @test */
    public function it_can_be_converted_to_an_array()
    {
        $data = [ 'foo' => 'bar', 'baz' => 'foo' ];

        $entity = new Entity($data);

        $this->assertEquals($data, $entity->toArray());
    }

    /** @test */
    public function it_converts_nested_entities_to_array()
    {
        $entityOne = new Entity([ 'foo' => 'bar' ]);
        $data = [ 'foo' => 'bar', 'baz' => $entityOne ];

        $entityTwo = new Entity($data);

        $expected = [ 'foo' => 'bar', 'baz' => [ 'foo' => 'bar' ] ];

        $this->assertEquals($expected, $entityTwo->toArray());
    }

    /** @test */
    public function it_converts_nested_collections_to_array()
    {
        $entityOne = new Entity([ 'foo' => 'bar' ]);

        $collection = new Collection([ $entityOne, $entityOne ]);

        $data = [ 'foo' => 'bar', 'baz' => $collection ];

        $entityTwo = new Entity($data);

        $expected = [
            'foo' => 'bar',
            'baz' => [
                [ 'foo' => 'bar' ],
                [ 'foo' => 'bar' ],
            ]
        ];

        $this->assertEquals($expected, $entityTwo->toArray());
    }

    /** @test */
    public function it_can_be_converted_to_json()
    {
        $entity = new Entity([ 'foo' => 'bar' ]);

        $this->assertEquals('{"foo":"bar"}', $entity->toJson());
    }

    /** @test */
    public function it_provides_magic_access_to_attributes()
    {
        $entity = new Entity;

        $entity->foo = 'bar';

        $this->assertEquals('bar', $entity->foo);
        $this->assertTrue(isset($entity->foo));
        unset($entity->foo);
        $this->assertFalse(isset($entity->foo));
    }

    /** @test */
    public function it_provides_array_access_to_attributes()
    {
        $entity = new Entity;

        $entity['foo'] = 'bar';

        $this->assertEquals('bar', $entity['foo']);
        $this->assertTrue(isset($entity['foo']));
        unset($entity['foo']);
        $this->assertFalse(isset($entity['foo']));
    }
}
