<?php

namespace PAO\Support\Facades;

/**
 * @see \Illuminate\Encryption\Encrypter
 */
class Captcha extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'captcha';
    }
}
