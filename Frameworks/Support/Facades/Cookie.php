<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Cookie
 *
 * @method static Cookie get(string $name)
 * @method static Cookie has(string $name)
 * @method static Cookie all()
 * @method static Cookie del(string $name)
 * @method static Cookie set(string $name, mixed $value = null, int $expire = 0, string $path = '/', string $domain = null, boolean $secure = false, boolean $httpOnly = true)
 */
class Cookie extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cookie';
    }
}
