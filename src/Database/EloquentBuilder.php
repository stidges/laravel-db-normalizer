<?php

namespace Stidges\LaravelDbNormalizer\Database;

use Illuminate\Database\Eloquent\Builder;
use Stidges\LaravelDbNormalizer\Normalizer;
use Stidges\LaravelDbNormalizer\NormalizerInterface;

class EloquentBuilder extends Builder
{
    /**
     * Normalizer instance used to normalize the results.
     *
     * @var \Stidges\LaravelDbNormalizer\NormalizerInterface
     */
    protected $normalizer;

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get($columns = array('*'))
    {
        $collection = parent::get($columns);

        return $this->getNormalizer()->normalize($collection->toArray());
    }

    /**
     * Get the Normalizer instance.
     *
     * @return \Stidges\LaravelDbNormalizer\NormalizerInterface
     */
    public function getNormalizer()
    {
        return $this->normalizer ?: new Normalizer;
    }

    /**
     * Set a Normalizer instance.
     *
     * @param  \Stidges\LaravelDbNormalizer\NormalizerInterface  $normalizer
     * @return void
     */
    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }
}
