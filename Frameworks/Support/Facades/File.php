<?php

namespace PAO\Support\Facades;


class File extends \Illuminate\Support\Facades\File
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
