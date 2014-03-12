<?php

namespace Stidges\LaravelDbNormalizer\Database;

use Stidges\LaravelDbNormalizer\Normalizer;
use Stidges\LaravelDbNormalizer\NormalizerInterface;
use Illuminate\Database\Connection as BaseConnection;

class Connection extends BaseConnection
{
    /**
     * Normalizer instance used to normalize the results.
     *
     * @var \Stidges\LaravelDbNormalizer\NormalizerInterface
     */
    protected $normalizer;

    /**
     * Run a select statement and return a single result.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return mixed
     */
    public function selectOne($query, $bindings = array())
    {
        $records = parent::select($query, $bindings);

        if (count($records) > 0)
        {
            return $this->getNormalizer()->normalize(reset($records));
        }

        return null;
    }

    /**
     * Run a select statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return \Stidges\LaravelDbNormalizer\Collection
     */
    public function select($query, $bindings = array())
    {
        $records = parent::select($query, $bindings);

        return $this->getNormalizer()->normalize($records);
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
