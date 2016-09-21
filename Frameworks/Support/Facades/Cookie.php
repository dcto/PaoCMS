<?php

namespace PAO\Support\Facades;


class Cookie extends \Illuminate\Support\Facades\Cookie
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cookie';
    }
}
