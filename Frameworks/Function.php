<?php
/**
 * Get the available container instance.
 *
 * @param  string  $make
 * @param  array   $parameters
 * @return \PAO\Application
 */
function app($make = null, $parameters = [])
{
    if (is_null($make)) {
        return \PAO\Application::getInstance();
    }

    return \PAO\Application::getInstance()->make($make, $parameters);
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
 * @return \PAO\Application
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
 * get path
 *
 * @return string
 */
function path()
{
    $path = dirname(__DIR__);
    array_map(function($arg)use(&$path){
      $path .= '/'. trim($arg, '/');
    }, func_get_args());

    if(getenv('ENV')){
       if(!is_dir($dir = dirname ($path))){
           if(!@mkdir($dir, 0777, true)){
               $error = error_get_last();
               throw new Exception($error['message']. ', Can not create directory: '.$dir, 1);
           }

       }
    }
    return $path;
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
 * @return \PAO\Http\Session\Session
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
 * @param bool $key
 * @param bool $value
 * @param int $time
 * @return \PAO\Cache\Driver\Driver
 */
function cache($key = false, $value = false, $time = 86400)
{
    if($key && $value){
        return make('cache')->set($key, $value, $time);
    }else if($key){
        return make('cache')->get($key);
    }
    return make('caches');
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