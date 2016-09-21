<?php

namespace PAO\Support\Facades;


class Config extends \Illuminate\Support\Facades\Config
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'config';
    }
}
