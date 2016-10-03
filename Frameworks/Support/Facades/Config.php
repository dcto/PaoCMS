<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Config
 *
 * @method static Config has(string $key)
 * @method static Config get(string $key, mixed $default = null)
 * @method static Config set(string $key, mixed $value = null)
 * @method static Config all()
 * @method static Config push($key, $value)
 */
class Config extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'config';
    }
}
