<?php

namespace Stidges\Tests;

use Stidges\LaravelDbNormalizer\Collection;
use Illuminate\Support\Collection as BaseCollection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_is_initializable()
    {
        $collection = new Collection;

        $this->assertTrue($collection instanceof Collection);
        $this->assertTrue($collection instanceof BaseCollection);
    }
}
