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
 * make alias name for app
 * @param null $make
 * @param array $parameters
 * @return object
 */
function make($make = null, $parameters = [])
{
    return app($make, $parameters);
}

/**
 * 服务加载器
 *
 * @param $make
 * @param array $parameters
 * @return mixed
 */
function service($make, $parameters = [])
{
    return app()->servcie($make, $parameters);
}

/**
 * get request url
 * @return mixed
 */
function url()
{
    return call_user_func_array(array(app('request'), 'url'), func_get_args());
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
 * request object
 * @return object
 */
function request()
{
    return app('request');
}

/**
 * response
 * @param $content
 * @param int $code
 * @param array $header
 * @return mixed
 */
function response($content = '', $status = 200, $header = array())
{
    return app('response')->make($content, $status, $header);
}


/**
 * redirect to url
 * @param $url
 * @param int $status
 * @param array $headers
 * @return mixed
 */
function redirect($url, $status = 302, $headers = [])
{
    return app('response')->redirect($url, $status, $headers);
}

/**
 * session object
 */
function session($k = false, $v = false)
{
    if($k && $v) {
        return app('session')->set($k, $v);
    }else if($k) {
        return app('session')->get($k);
    }else{
        return app('session');
    }
}

/**
 * cookie object
 */
function cookie($k = false, $v = false)
{
    if($k && $v) {
        return app('cookie')->set($k, $v);
    }else if($k) {
        return app('cookie')->get($k);
    }else{
        return app('cookie');
    }
}

/**
 * view response
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

/**
 * json response
 * @param array $data
 * @param int $status
 * @param array $headers
 * @return mixed
 */
function json($data = [], $status = 200, array $headers = [])
{
    return app('response')->json($data, $status, $headers);
}


/**
 * random string
 * @param int $length
 */
function random($length = 16)
{
    return Str::quickRandom($length);
}


//调试函数,方便显示调试函数的位置和文件
function dump()
{
    $args = func_get_args();

    // 调用栈,debug_backtrace()
    $backtrace = debug_backtrace();

    $file = $backtrace[0]['file'];
    $line = $backtrace[0]['line'];
    echo "<b>$file: $line</b><hr />";
    echo "<pre>";
    foreach ($args as $arg) {
        var_dump($arg);
    }
    echo "</pre>";
    die;
}