<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class DB
 *
 * @method static DB
 */
class DB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'db';
    }
}
