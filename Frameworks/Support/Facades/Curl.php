<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Curl
 * @method static \PAO\Http\Curl\Curl curl()
 * @method static \PAO\Http\Curl\Curl get(string $url, array $vars = array())
 * @method static \PAO\Http\Curl\Curl post(string $url, array $vars = array(), bool $encrypt = null)
 * @method static \PAO\Http\Curl\Curl options(string $key, array $var = null)
 * @method static \PAO\Http\Curl\Curl headers(string $key, array $var)
 * @method static \PAO\Http\Curl\Curl cookies(string $key, array $var)
 * @method static \PAO\Http\Curl\Curl referer(string $referer)
 * @method static \PAO\Http\Curl\Curl userAgent(string $userAgent)
 * @method static \PAO\Http\Curl\Curl verbose(bool $on = true)
 * @method static \PAO\Http\Curl\Curl retry(int $times = 0)
 * @method static \PAO\Http\Curl\Curl request(string $method, string $url, array $vars = array(), bool $encrypt = null)
 * @method static \PAO\Http\Curl\Curl close()
 */
class Curl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'curl';
    }
}
