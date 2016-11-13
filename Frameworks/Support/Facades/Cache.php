<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Cache
 *
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
