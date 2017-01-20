<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Session
 *
 * @method static Session start()
 * @method static Session id()
 * @method static Session has(string $name)
 * @method static Session get(string $name, mixed $default = null)
 * @method static Session set(string $name, mixed $value)
 * @method static Session del(string $name)
 * @method static Session all()
 * @method static Session clear()
 * @method static Session count()
 * @method static Session regenerate($delete = false)
 * @method static Session isStarted()
 */
class Session extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'session';
    }
}
