<?php

namespace PAO\Support\Facades;


use Illuminate\Support\Facades\Facade;

class File extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'file';
    }
}
