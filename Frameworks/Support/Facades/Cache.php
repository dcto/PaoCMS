<?php

namespace PAO\Support\Facades;


class Cache extends \Illuminate\Support\Facades\Cache
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
