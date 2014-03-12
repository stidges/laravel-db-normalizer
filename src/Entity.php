<?php

namespace Stidges\LaravelDbNormalizer;

use Closure;
use ArrayAccess;
use Illuminate\Support\Contracts\JsonableInterface;
use Illuminate\Support\Contracts\ArrayableInterface;

class Entity implements ArrayAccess, ArrayableInterface, JsonableInterface
{
    /**
     * The original attributes of the entity.
     *
     * @var array
     */
    protected $original = [];

    /**
     * The attributes of the entity.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new Entity.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = array())
    {
        $this->set($attributes);

        $this->original = $this->attributes;
    }

    /**
     * Set an attribute on the entity. An array can be passed to set
     * multiple attributes at once.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value = null)
    {
        if (is_array($key))
        {
            foreach ($key as $k => $v)
            {
                $this->set($k, $v);
            }
        }
        else
        {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Get an attribute from the entity. Returns the default value if
     * the attribute does not exist.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->attributes))
        {
            return $this->attributes[$key];
        }

        return $default instanceof Closure ? $default() : $default;
    }

    /**
     * Get an array of the modified (dirty) attributes from the entity.
     *
     * @return array
     */
    public function getDirtyAttributes()
    {
        $dirty = array();

        foreach ($this->attributes as $key => $value)
        {
            if (! array_key_exists($key, $this->original) || $value !== $this->original[$key])
            {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Get all the attributes from the entity.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get the entity as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();

        foreach ($this->attributes as $key => $value)
        {
            if ($value instanceof ArrayableInterface)
            {
                $array[$key] = $value->toArray();
            }
            else
            {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Convert the entity to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Determine if the given offset exists.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * Get the value for a given offset.
     *
     * @param  string  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $offset
     * @param  mixed   $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  string  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param  string  $key
     * @return void
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Dynamically unset an attribute.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }
}
