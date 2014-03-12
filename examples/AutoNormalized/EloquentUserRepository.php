<?php

namespace Stidges\Examples\AutoNormalized;

use Stidges\Examples\User;
use Stidges\Examples\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * User model.
     *
     * @var \Stidges\Examples\User
     */
    protected $model;

    /**
     * Create a new user repository.
     *
     * @param  \Stidges\Examples\User  $model
     * @return void
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Get all users.
     *
     * @return \Stidges\LaravelDbNormalizer\Collection
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get a user by its ID.
     *
     * @param  mixed  $id
     * @return \Stidges\LaravelDbNormalizer\Entity
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create a new user.
     *
     * @param  \Stidges\LaravelDbNormalizer\Entity  $user
     * @return bool
     */
    public function create(Entity $user)
    {
        $model = $this->model->newInstance($user->toArray());

        return $model->save();
    }

    /**
     * Update an existing user.
     *
     * @param  \Stidges\LaravelDbNormalizer\Entity  $user
     * @return bool
     */
    public function update(Entity $user)
    {
        $model = $this->model->newInstance($user->toArray(), true);
        $model->id = $user->id;

        return $user->getDirtyAttributes() ? $model->save() : $model->touch();
    }
}
