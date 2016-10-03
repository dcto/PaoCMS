<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Cache
 *
 * @method static \PAO\FileSystem\Files file(string $file = 'default')
 * @method static \Redis redis(string $server = 'default');
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
