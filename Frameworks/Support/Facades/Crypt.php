<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Crypt
 *
 * @method static Crypt key(string $str = null)
 * @method static Crypt en(string $str, $key = null)
 * @method static Crypt de(string $str, $key = null)
 */
class Crypt extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'crypt';
    }
}
