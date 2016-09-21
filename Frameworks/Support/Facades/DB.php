<?php

namespace PAO\Support\Facades;


class DB extends \Illuminate\Support\Facades\DB
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
