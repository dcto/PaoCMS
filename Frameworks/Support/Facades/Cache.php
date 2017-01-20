<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Cache
 *
 * @method static \PAO\Cache\Driver\Driver has(string $key)
 * @method static \PAO\Cache\Driver\Driver get(string $key)
 * @method static \PAO\Cache\Driver\Driver set(string $key, mixed $value, int $time = 86400)
 * @method static \PAO\Cache\Driver\Driver gets(array $key)
 * @method static \PAO\Cache\Driver\Driver sets(array $values, $time = 86400)
 * @method static \PAO\Cache\Driver\Driver increment(string $key, int $value = 1, int $time = 86400)
 * @method static \PAO\Cache\Driver\Driver decrement(string $key, int $value = 1)
 * @method static \PAO\Cache\Driver\Driver save(string $key, mixed $value)
 * @method static \PAO\Cache\Driver\Driver del(string $key)
 * @method static \PAO\Cache\Driver\Driver flush()
 * @method static \PAO\Cache\Driver\Driver prefix($prefix = false)
 * @method static \PAO\Cache\Driver\ApcDriver apc(string $file = 'default')
 * @method static \PAO\Cache\Driver\FilesDriver file(string $file = 'default')
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
