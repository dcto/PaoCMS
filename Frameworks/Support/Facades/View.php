<?php

namespace PAO\Support\Facades;


class View extends \Illuminate\Support\Facades\View
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'view';
    }
}
