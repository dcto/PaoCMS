<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Request
 *
 * @method static Request is()
 * @method static Request url()
 * @method static Request uri(string $case = null)
 * @method static Request get(string $key = null, mixed $default = null, boolean $deep = false)
 * @method static Request all(array $key = null)
 * @method static Request not(array $key = null)
 * @method static Request has(mixed $key)
 * @method static Request take(mixed $key)
 * @method static Request json(string $key = null, mixed $default = null)
 * @method static Request input(string $key = null, mixed $default = null)
 * @method static Request header(string $key = null, mixed $default = null)
 * @method static Request server(string $key = null, mixed $default = null)
 * @method static Request file(string $key = null, mixed $default = null)
 * @method static Request files()
 * @method static Request hasFile(string $key)
 * @method static Request cookie(string $key = null)
 * @method static Request isJson()
 * @method static Request method()
 * @method static Request ip()
 * @method static Request ips()
 * @method static Request path()
 * @method static Request root()
 * @method static Request baseUrl()
 * @method static Request scheme()
 * @method static Request domain()
 * @method static Request segment()
 * @method static Request segments()
 * @method static Request secure()
 */
class Request extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'request';
    }
}
