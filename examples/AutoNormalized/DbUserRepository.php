<?php

namespace Stidges\Examples\AutoNormalized;

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
     * The fields that are fillable.
     *
     * @var array
     */
    protected $fillable = [ 'name', 'email' ];

    /**
     * Create a new user repository.
     *
     * @param  \Illuminate\Database\DatabaseManager  $db
     * @return void
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Get all users.
     *
     * @return \Stidges\LaravelDbNormalizer\Collection
     */
    public function getAll()
    {
        return $this->db->table($this->table)
                        ->get();
    }

    /**
     * Get a user by its ID.
     *
     * @param  mixed  $id
     * @return \Stidges\LaravelDbNormalizer\Entity
     */
    public function getById($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->first();
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
