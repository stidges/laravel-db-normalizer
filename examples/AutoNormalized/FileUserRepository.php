<?php
/**
 * Note! File system is not intercepted!
 * This will still use the 'not auto-normalized' way!
 */
namespace Stidges\Examples\AutoNormalized;

use Stidges\Examples\UserRepositoryInterface;
use Illuminate\Filesystem\Filesystem;
use Stidges\LaravelDbNormalizer\Normalizer;

class FileUserRepository implements UserRepositoryInterface
{
    /**
     * Path to the users file.
     *
     * @var string
     */
    protected $path = __DIR__ . '/../users.php';

    /**
     * Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Normalizer instance.
     *
     * @var \Stidges\LaravelDbNormalizer\Normalizer
     */
    protected $normalizer;

    /**
     * Create a new user repository.
     *
     * @param  \Illuminate\Filesystem\Filesystem        $files
     * @param  \Stidges\LaravelDbNormalizer\Normalizer  $normalizer
     * @return void
     */
    public function __construct(Filesystem $files, Normalizer $normalizer)
    {
        $this->files = $files;
        $this->normalizer = $normalizer;
    }

    /**
     * Get all users.
     *
     * @return \Stidges\LaravelDbNormalizer\Collection
     */
    public function getAll()
    {
        $users = $this->files->getRequire($this->path);

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
        $users = $this->files->getRequire($this->path);
        $result = [];

        foreach ($users as $user)
        {
            if ($user['id'] == $id)
            {
                $result = $user;
                break;
            }
        }

        return $this->normalizer->normalize($result);
    }

    /**
     * Create a new user.
     *
     * @param  \Stidges\LaravelDbNormalizer\Entity  $user
     * @throws \RuntimeException
     */
    public function create(Entity $user)
    {
        throw new \RuntimeException('This implementation does not support altering.');
    }

    /**
     * Update an existing user.
     *
     * @param  \Stidges\LaravelDbNormalizer\Entity  $user
     * @throws \RuntimeException
     */
    public function update(Entity $user)
    {
        throw new \RuntimeException('This implementation does not support altering.');
    }
}
