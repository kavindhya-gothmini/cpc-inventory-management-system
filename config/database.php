<?php

use Illuminate\Support\Str;
use Pdo\Mysql;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection
    |--------------------------------------------------------------------------
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [

        /*
        |--------------------------------------------------------------------------
        | SQLite
        |--------------------------------------------------------------------------
        */

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env(
                'DB_DATABASE',
                database_path('database.sqlite')
            ),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
            'transaction_mode' => 'DEFERRED',
        ],

        /*
        |--------------------------------------------------------------------------
        | MySQL — Local CPC Inventory Application
        |--------------------------------------------------------------------------
        */

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'cpc_inventory'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env(
                'DB_COLLATION',
                'utf8mb4_unicode_ci'
            ),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,

            'options' => extension_loaded('pdo_mysql')
                ? array_filter([
                    (PHP_VERSION_ID >= 80500
                        ? Mysql::ATTR_SSL_CA
                        : PDO::MYSQL_ATTR_SSL_CA
                    ) => env('MYSQL_ATTR_SSL_CA'),
                ])
                : [],
        ],

        /*
        |--------------------------------------------------------------------------
        | MariaDB
        |--------------------------------------------------------------------------
        */

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'cpc_inventory'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env(
                'DB_COLLATION',
                'utf8mb4_unicode_ci'
            ),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,

            'options' => extension_loaded('pdo_mysql')
                ? array_filter([
                    (PHP_VERSION_ID >= 80500
                        ? Mysql::ATTR_SSL_CA
                        : PDO::MYSQL_ATTR_SSL_CA
                    ) => env('MYSQL_ATTR_SSL_CA'),
                ])
                : [],
        ],

        /*
        |--------------------------------------------------------------------------
        | PostgreSQL
        |--------------------------------------------------------------------------
        */

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => env('DB_SSLMODE', 'prefer'),
        ],

        /*
        |--------------------------------------------------------------------------
        | CPC HR Database
        |--------------------------------------------------------------------------
        */

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('CPC_SQLSRV_URL'),
            'host' => env(
                'CPC_SQLSRV_HOST',
                '192.168.100.71'
            ),
            'port' => env('CPC_SQLSRV_PORT', '1433'),
            'database' => env(
                'CPC_SQLSRV_DATABASE',
                'CPC_HAPIS_JAN'
            ),
            'username' => env('CPC_SQLSRV_USERNAME'),
            'password' => env('CPC_SQLSRV_PASSWORD'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('CPC_SQLSRV_ENCRYPT', 'yes'),
            'trust_server_certificate' => env(
                'CPC_SQLSRV_TRUST_SERVER_CERTIFICATE',
                'false'
            ),
        ],

        /*
        |--------------------------------------------------------------------------
        | CPC Help Desk and Asset Management Database
        |--------------------------------------------------------------------------
        */

        'sqlsrv_assets' => [
            'driver' => 'sqlsrv',
            'url' => env('CPC_ASSET_SQLSRV_URL'),
            'host' => env(
                'CPC_SQLSRV_HOST',
                '192.168.100.71'
            ),
            'port' => env('CPC_SQLSRV_PORT', '1433'),
            'database' => env(
                'CPC_ASSET_SQLSRV_DATABASE',
                'CPC_HelpDesk_And_Asset_Management'
            ),
            'username' => env(
                'CPC_ASSET_SQLSRV_USERNAME',
                env('CPC_SQLSRV_USERNAME')
            ),
            'password' => env(
                'CPC_ASSET_SQLSRV_PASSWORD',
                env('CPC_SQLSRV_PASSWORD')
            ),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('CPC_SQLSRV_ENCRYPT', 'yes'),
            'trust_server_certificate' => env(
                'CPC_SQLSRV_TRUST_SERVER_CERTIFICATE',
                'false'
            ),
        ],

    ], // End database connections

    /*
    |--------------------------------------------------------------------------
    | Migration Repository
    |--------------------------------------------------------------------------
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis
    |--------------------------------------------------------------------------
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env(
                'REDIS_PREFIX',
                Str::slug(
                    (string) env('APP_NAME', 'laravel')
                ) . '-database-'
            ),
            'persistent' => env('REDIS_PERSISTENT', false),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
            'max_retries' => env('REDIS_MAX_RETRIES', 3),
            'backoff_algorithm' => env(
                'REDIS_BACKOFF_ALGORITHM',
                'decorrelated_jitter'
            ),
            'backoff_base' => env('REDIS_BACKOFF_BASE', 100),
            'backoff_cap' => env('REDIS_BACKOFF_CAP', 1000),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
            'max_retries' => env('REDIS_MAX_RETRIES', 3),
            'backoff_algorithm' => env(
                'REDIS_BACKOFF_ALGORITHM',
                'decorrelated_jitter'
            ),
            'backoff_base' => env('REDIS_BACKOFF_BASE', 100),
            'backoff_cap' => env('REDIS_BACKOFF_CAP', 1000),
        ],

    ],

];