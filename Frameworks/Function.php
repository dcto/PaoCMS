<?php

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
    /**
     * Get the available container instance.
     *
     * @param  string  $make
     * @param  array   $parameters
     * @return object
     */
function app($make = null, $parameters = [])
{
    if (is_null($make)) {
        return Container::getInstance();
    }

    return Container::getInstance()->make($make, $parameters);
}


/**
 * get the language
 * @return mixed
 */
function lang()
{
   return call_user_func_array(array(app('lang'), 'get'), func_get_args());
}

/**
 * get config
 * @return mixed
 */
function config()
{
    return call_user_func_array(array(app('config'), 'get'), func_get_args());
}
