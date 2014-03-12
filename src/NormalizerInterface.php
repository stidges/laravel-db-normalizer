<?php

namespace Stidges\LaravelDbNormalizer;

interface NormalizerInterface
{
    /**
     * Normalize the given array to a collection / entity.
     *
     * @param  array  $data
     * @return \Stidges\LaravelDbNormalizer\Collection|\Stidges\LaravelDbNormalizer\Entity
     */
    public function normalize(array $data);
}
