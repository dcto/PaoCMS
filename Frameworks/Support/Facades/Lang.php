<?php

namespace PAO\Support\Facades;


use Illuminate\Support\Facades\Facade;

class Lang extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lang';
    }
}
