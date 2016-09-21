<?php

namespace PAO\Support\Facades;

class Router extends \Illuminate\Support\Facades\Route
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'router';
    }
}
