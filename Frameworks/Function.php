<?php

use Illuminate\Container\Container;
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


/**
 * response
 * @param $content
 * @param int $code
 * @param array $header
 * @return mixed
 */
function response($content, $status = 200, $header = array())
{
    return app('response')->make($content, $status, $header);
}


/**
 * view
 * @param $view
 * @param array $data
 * @param int $status
 * @param array $headers
 * @return mixed
 */
function view($view, array $data = [], $status = 200, array $headers = [])
{
    return app('response')->view($view, $data, $status, $headers);
}