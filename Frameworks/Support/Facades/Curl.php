<?php

namespace PAO\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Curl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'curl';
    }
}
