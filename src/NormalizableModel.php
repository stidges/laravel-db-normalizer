<?php

namespace Stidges\LaravelDbNormalizer;

use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Database\Eloquent\Model;
use Stidges\LaravelDbNormalizer\Database\EloquentBuilder as Builder;

abstract class NormalizableModel extends Model
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Stidges\LaravelDbNormalizer\Database\EloquentBuilder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Create a new model instance that is existing.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function newFromBuilder($attributes = array())
    {
        $instance = $this->newInstance([], true);

        if ($attributes instanceof ArrayableInterface)
        {
            $attributes = $attributes->toArray();
        }
        else
        {
            $attributes = (array) $attributes;
        }

        $instance->setRawAttributes($attributes, true);

        return $instance;
    }
}
