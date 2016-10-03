<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class DB
 *
 * @method static DB connection(string $connection = 'default');
 * @method static DB extend(string $name, callable $resolver)
 * @method static Illuminate\Database\Eloquent\Builder table(string $table, string $connection = 'default');
 * @method static Illuminate\Database\Schema\Builder schema(string $connection = 'default');
 * @method static DB getConnection(string $name = 'default');
 * @method static DB addConnection(array $config, $name = 'default')
 * @method static DB bootEloquent()
 * @method static Illuminate\Database\Capsule\Manager setFetchMode($fetchMode)
 * @method static DB getDatabaseManager()
 * @method static DB getEventDispatcher()
 * @method static DB setEventDispatcher()
 */
class DB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'db';
    }
}
