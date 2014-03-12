<?php

namespace Stidges\Tests;

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Stidges\LaravelDbNormalizer\Database\SQLiteConnection;
use Stidges\LaravelDbNormalizer\DbNormalizerServiceProvider;

abstract class DbTestCase extends \PHPUnit_Framework_TestCase
{
    protected $capsule;
    protected $connection;

    public function setUp()
    {
        $this->capsule = new Capsule;

        $this->bootServiceProvider();

        $this->capsule->addConnection($this->getDatabaseConfig());
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();

        $this->connection = $this->capsule->getConnection();

        $this->migrateUp();
        $this->seed();
    }

    public function tearDown()
    {
        $this->migrateDown();

        $this->connection = null;
        $this->capsule = null;
    }

    protected function bootServiceProvider()
    {
        $container = $this->capsule->getContainer();

        $this->getServiceProvider($container)->register();
    }

    protected function getServiceProvider($container)
    {
        return new DbNormalizerServiceProvider($container);
    }

    protected function getDatabaseConfig()
    {
        return [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ];
    }

    protected function migrateUp()
    {
        $this->getSchemaBuilder()->create('table', function($table)
        {
            $table->increments('id');
            $table->string('name');
        });
    }

    protected function seed()
    {
        $this->connection->table('table')
                         ->insert($this->getData());
    }

    protected function migrateDown()
    {
        $this->getSchemaBuilder()->drop('table');
    }

    protected function getSchemaBuilder()
    {
        return $this->connection->getSchemaBuilder();
    }

    protected function getData()
    {
        $data[] = [ 'id' => 1, 'name' => 'foo' ];
        $data[] = [ 'id' => 2, 'name' => 'bar' ];

        return $data;
    }
}
