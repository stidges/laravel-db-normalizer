<?php

namespace Stidges\Examples\AutoNormalized;

use Stidges\LaravelDbNormalizer\NormalizableModel;

class User extends NormalizableModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name', 'email' ];
}
