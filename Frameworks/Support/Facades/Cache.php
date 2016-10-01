<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Cache
 *
 * @method static Cache file(string $cache = 'default')
 * @method static Cache redis(string $server = 'default')
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
