<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Captcha
 *
 * @method static Captcha is(string $input, $case = false)
 * @method static Captcha make(int $width = 100, int $height = 30, int $obstruct = 5)
 * @method static Captcha fonts(array $fonts)
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
