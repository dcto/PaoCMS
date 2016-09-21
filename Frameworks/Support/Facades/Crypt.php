<?php

namespace PAO\Support\Facades;


class Crypt extends \Illuminate\Support\Facades\Crypt
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'crypt';
    }
}
