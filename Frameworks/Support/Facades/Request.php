<?php

namespace PAO\Support\Facades;


class Request extends \Illuminate\Support\Facades\Request
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'request';
    }
}
