<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Cache
 *
 * @method static \PAO\Cache\Cache has(string $key)
 * @method static \PAO\Cache\Cache get(string $key)
 * @method static \PAO\Cache\Cache set(string $key, mixed $value, int $time = 86400)
 * @method static \PAO\Cache\Cache gets(array $key)
 * @method static \PAO\Cache\Cache sets(array $key, mixed $value, int $time = 86400)
 * @method static \PAO\Cache\Cache increment(string $key, int $value = 1, int $time = 86400)
 * @method static \PAO\Cache\Cache decrement(string $key, int $value = 1)
 * @method static \PAO\Cache\Cache save(string $key, mixed $value)
 * @method static \PAO\Cache\Cache del(string $key)
 * @method static \PAO\Cache\Cache flush()
 * @method static \PAO\Cache\Cache prefix($prefix = false)
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
