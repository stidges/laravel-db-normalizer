<?php

namespace Stidges\LaravelDbNormalizer;

class Normalizer implements NormalizerInterface
{
    /**
     * Normalize the given array to a collection / entity.
     *
     * @param  array  $data
     * @return \Stidges\LaravelDbNormalizer\Collection|\Stidges\LaravelDbNormalizer\Entity
     */
    public function normalize(array $data)
    {
        if ($this->isAssociative($data)) {
            return $this->normalizeEntity($data);
        }

        return $this->normalizeCollection($data);
    }

    /**
     * Determine whether the given array is associative or not.
     *
     * @param  array  $array
     * @return bool
     */
    protected function isAssociative(array $array)
    {
        return (bool) count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * Normalize the given array to an Entity.
     *
     * @param  array  $entity
     * @return \Stidges\LaravelDbNormalizer\Entity
     */
    protected function normalizeEntity(array $entity)
    {
        $normalized = [];

        foreach ($entity as $attribute => $value)
        {
            if ($this->isCollection($value))
            {
                $value = $this->normalizeCollection($value);
            }
            elseif ($this->isEntity($value))
            {
                $value = $this->normalizeEntity($value);
            }

            $normalized[$attribute] = $value;
        }

        return $this->newEntity($normalized);
    }

    /**
     * Normalize the given array to a Collection.
     *
     * @param  array  $collection
     * @return \Stidges\LaravelDbNormalizer\Collection
     */
    protected function normalizeCollection(array $collection)
    {
        $normalized = [];

        foreach ($collection as $entity)
        {
            $normalized[] = $this->normalizeEntity($entity);
        }

        return $this->newCollection($normalized);
    }

    /**
     * Determine whether the given value should be cast to an Entity.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isEntity($value)
    {
        return is_array($value) && $this->isAssociative($value);
    }

    /**
     * Determine whether the given value should be cast to a Collection.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isCollection($value)
    {
        return is_array($value) && ! $this->isAssociative($value);
    }

    /**
     * Get a new Collection instance.
     *
     * @param  array  $entities
     * @return \Stidges\LaravelDbNormalizer\Collection
     */
    protected function newCollection(array $entities = array())
    {
        return new Collection($entities);
    }

    /**
     * Get a new Entity instance.
     *
     * @param  array  $attributes
     * @return \Stidges\LaravelDbNormalizer\Entity
     */
    protected function newEntity(array $attributes = array())
    {
        return new Entity($attributes);
    }
}
