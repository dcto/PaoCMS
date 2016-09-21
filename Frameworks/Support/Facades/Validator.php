<?php

namespace PAO\Support\Facades;

class Validator extends \Illuminate\Support\Facades\Validator
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'validator';
    }
}
