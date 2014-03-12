<?php

namespace Stidges\Examples\NotAutoNormalized;

use Stidges\Examples\User;
use Stidges\Examples\UserRepositoryInterface;
use Stidges\LaravelDbNormalizer\Normalizer;

class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * User model.
     *
     * @var \Stidges\Examples\User
     */
    protected $model;

    /**
     * Normalizer instance.
     *
     * @var \Stidges\LaravelDbNormalizer\Normalizer
     */
    protected $normalizer;

    /**
     * Create a new user repository.
     *
     * @param  \Stidges\Examples\User                   $model
     * @param  \Stidges\LaravelDbNormalizer\Normalizer  $normalizer
     * @return void
     */
    public function __construct(User $model, Normalizer $normalizer)
    {
        $this->model = $model;
        $this->normalizer = $normalizer;
    }

    /**
     * Get all users.
     *
     * @return \Stidges\LaravelDbNormalizer\Collection
     */
    public function getAll()
    {
        $users = $this->model->all();

        return $this->normalizer->normalize($users->toArray());
    }

    /**
     * Get a user by its ID.
     *
     * @param  mixed  $id
     * @return \Stidges\LaravelDbNormalizer\Entity
     */
    public function getById($id)
    {
        $user = $this->model->find($id);

        return $this->normalizer->normalize($user->toArray());
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
