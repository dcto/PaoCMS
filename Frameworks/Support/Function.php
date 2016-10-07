<?php

use Illuminate\Container\Container;
/**
 * Get the available container instance.
 *
 * @param  string  $make
 * @param  array   $parameters
 * @return \PAO\Frameworks|Illuminate\Container\Container
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
 * @return mixed
 */
function make($make = null, $parameters = [])
{
    return app($make, $parameters);
}

/**
 * take alias name for app
 *
 * @param null $make
 * @param array $parameters
 * @return Container|\PAO\Frameworks
 */
function take($make = null, $parameters = [])
{
    return app($make, $parameters);
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
 * @return string
 */
function config()
{
    return call_user_func_array(array(app('config'), 'get'), func_get_args());
}

/**
 * request object
 * @return \PAO\Http\Request
 */
function request()
{
    return make('request');
}

/**
 * response
 * @param $content
 * @param int $code
 * @param array $header
 * @return \PAO\Http\Response
 */
function response($content = '', $status = 200, $header = array())
{
    return make('response')->make($content, $status, $header);
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
    return make('response')->redirect($url, $status, $headers);
}

/**
 * session object
 * @return \PAO\Http\Session
 */
function session($k = false, $v = false)
{
    if($k && $v) {
        return make('session')->set($k, $v);
    }else if($k) {
        return make('session')->get($k);
    }else{
        return make('session');
    }
}

/**
 * cookie object
 * @return \PAO\Http\Cookie
 */
function cookie($k = false, $v = false)
{
    if($k && $v) {
        return make('cookie')->set($k, $v);
    }else if($k) {
        return make('cookie')->get($k);
    }else{
        return make('cookie');
    }
}

/**
 * \DB::table function
 * @param $table
 * @return \Illuminate\Database\Eloquent\Builder
 */
function DB($table)
{
    return \DB::table($table);
}

/**
 * view response
 * @param $view
 * @param array $data
 * @param int $status
 * @param array $headers
 * @return \PAO\View
 */
function view($view, array $data = [], $status = 200, array $headers = [])
{
    return make('response')->view($view, $data, $status, $headers);
}

/**
 * json response
 * @param array $data
 * @param int $status
 * @param array $headers
 * @return \Symfony\Component\HttpFoundation\JsonResponse
 */
function json($data = [], $status = 200, array $headers = [])
{
    return make('response')->json($data, $status, $headers);
}

/**
 * input method
 * @param null $key
 * @param null $default
 * @return string
 */
function input($key = null, $default = null)
{
    return make('request')->input($key, $default);
}

/**
 * redis
 * @param string $server
 * @return \Redis
 */
function redis($server = 'default')
{
    return make('cache')->redis($server);
}

/**
 * random string
 * @param int $length
 * @return string
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