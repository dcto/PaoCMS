<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Cache
 *
 * @method static \PAO\Cache\Driver\DriverInterface has(string $key)
 * @method static \PAO\Cache\Driver\DriverInterface get(string $key)
 * @method static \PAO\Cache\Driver\DriverInterface set(string $key, mixed $value, int $time = 86400)
 * @method static \PAO\Cache\Driver\DriverInterface gets(array $key)
 * @method static \PAO\Cache\Driver\DriverInterface sets(array $values, $time = 86400)
 * @method static \PAO\Cache\Driver\DriverInterface increment(string $key, int $value = 1, int $time = 86400)
 * @method static \PAO\Cache\Driver\DriverInterface decrement(string $key, int $value = 1)
 * @method static \PAO\Cache\Driver\DriverInterface save(string $key, mixed $value)
 * @method static \PAO\Cache\Driver\DriverInterface del(string $key)
 * @method static \PAO\Cache\Driver\DriverInterface flush()
 * @method static \PAO\Cache\Driver\DriverInterface prefix($prefix = false)
 * @method static \PAO\Cache\Driver\ApcDriver apc(string $file = 'default')
 * @method static \PAO\Cache\Driver\FileDriver file(string $file = 'default')
 * @method static \PAO\Cache\Driver\RedisDriver|\Redis redis(string $server = 'default');
 */
class Cache extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cache';
    }
}
