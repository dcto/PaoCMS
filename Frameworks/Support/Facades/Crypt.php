<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Crypt
 *
 * @method static Crypt key(string $str = null)
 * @method static Crypt encrypt(string $str)
 * @method static Crypt decrypt(string $str)
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
