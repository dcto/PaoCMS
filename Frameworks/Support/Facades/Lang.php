<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Lang
 *
 * @method static Lang get(string $key)
 * @method static Lang all()
 * @method static Lang set(string $key, string $value)
 * @method static Lang getLang(string $key, string $value)
 * @method static Lang setLang(string $language)
 */
class Lang extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lang';
    }
}
