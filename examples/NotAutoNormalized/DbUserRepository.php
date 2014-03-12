<?php

namespace Stidges\Examples\NotAutoNormalized;

use Stidges\Examples\UserRepositoryInterface;
use Illuminate\Database\DatabaseManager;
use Stidges\LaravelDbNormalizer\Normalizer;

class DbUserRepository implements UserRepositoryInterface
{
    /**
     * DatabaseManager instance.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $db;

    /**
     * The table name for the users.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Normalizer instance.
     *
     * @var \Stidges\LaravelDbNormalizer\Normalizer
     */
    protected $normalizer;

    /**
     * Create a new user repository.
     *
     * @param  \Illuminate\Database\DatabaseManager     $db
     * @param  \Stidges\LaravelDbNormalizer\Normalizer  $normalizer
     * @return void
     */
    public function __construct(DatabaseManager $db, Normalizer $normalizer)
    {
        $this->db = $db;
        $this->normalizer = $normalizer;
    }

    /**
     * Get all users.
     *
     * @return \Stidges\LaravelDbNormalizer\Collection
     */
    public function getAll()
    {
        $users = $this->db->table($this->table)->get();

        return $this->normalizer->normalize($users);
    }

    /**
     * Get a user by its ID.
     *
     * @param  mixed  $id
     * @return \Stidges\LaravelDbNormalizer\Entity
     */
    public function getById($id)
    {
        $user = $this->db->table($this->table)->where('id', $id)->first();

        return $this->normalizer->normalize($user);
    }

    /**
     * Create a new user.
     *
     * @param  \Stidges\LaravelDbNormalizer\Entity  $user
     * @return bool
     */
    public function create(Entity $user)
    {
        $data = array_only($user->toArray(), $this->fillable);

        return $this->db->table($this->table)
                        ->insert($data);
    }

    /**
     * Update an existing user.
     *
     * @param  \Stidges\LaravelDbNormalizer\Entity  $user
     * @return bool
     */
    public function update(Entity $user)
    {
        $data = array_only($user->toArray(), $this->fillable);

        return (bool) $this->db->table($this->table)
                               ->where('id', $user->id)
                               ->update($data);
    }
}
