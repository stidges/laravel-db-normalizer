<?php

namespace Stidges\LaravelDbNormalizer;

use Illuminate\Support\ServiceProvider;
use Stidges\LaravelDbNormalizer\Database\MySqlConnection;
use Stidges\LaravelDbNormalizer\Database\SQLiteConnection;
use Stidges\LaravelDbNormalizer\Database\PostgresConnection;
use Stidges\LaravelDbNormalizer\Database\SqlServerConnection;

class DbNormalizerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMySqlConnection();
        $this->registerPostgresConnection();
        $this->registerSQLiteConnection();
        $this->registerSqlServerConnection();
    }

    /**
     * Register the MySql connection.
     *
     * @return void
     */
    protected function registerMySqlConnection()
    {
        $this->app->singleton('db.connection.mysql', function ($app, $parameters)
        {
            list($connection, $database, $prefix, $config) = $parameters;

            return new MySqlConnection($connection, $database, $prefix, $config);
        });
    }

    /**
     * Register the Postgres connection.
     *
     * @return void
     */
    protected function registerPostgresConnection()
    {
        $this->app->singleton('db.connection.pgsql', function ($app, $parameters)
        {
            list($connection, $database, $prefix, $config) = $parameters;

            return new PostgresConnection($connection, $database, $prefix, $config);
        });
    }

    /**
     * Register the SQLite connection.
     *
     * @return void
     */
    protected function registerSQLiteConnection()
    {
        $this->app->singleton('db.connection.sqlite', function ($app, $parameters)
        {
            list($connection, $database, $prefix, $config) = $parameters;

            return new SQLiteConnection($connection, $database, $prefix, $config);
        });
    }

    /**
     * Register the SqlServer connection.
     *
     * @return void
     */
    protected function registerSqlServerConnection()
    {
        $this->app->singleton('db.connection.sqlsrv', function ($app, $parameters)
        {
            list($connection, $database, $prefix, $config) = $parameters;

            return new SqlServerConnection($connection, $database, $prefix, $config);
        });
    }
}
