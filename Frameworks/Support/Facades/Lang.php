<?php

namespace PAO\Support\Facades;


class Lang extends \Illuminate\Support\Facades\Lang
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
