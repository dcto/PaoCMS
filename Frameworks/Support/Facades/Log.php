<?php

namespace PAO\Support\Facades;


class Log extends \Illuminate\Support\Facades\Log
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'log';
    }
}
