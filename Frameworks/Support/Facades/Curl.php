<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Curl
 * @method static Curl curl()
 * @method static Curl get(string $url, array $vars = array())
 * @method static Curl post(string $url, array $vars = array(), bool $encrypt = null)
 * @method static Curl options(string $key, array $var = null)
 * @method static Curl headers(string $key, array $var)
 * @method static Curl cookies(string $key, array $var)
 * @method static Curl referer(string $referer)
 * @method static Curl userAgent(string $userAgent)
 * @method static Curl verbose(bool $on = true)
 * @method static Curl retry(int $times = 0)
 * @method static Curl request(string $method, string $url, array $vars = array(), bool $encrypt = null)
 * @method static Curl close()
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
