<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Log
 *
 * @method static Log log($level, string $message, array $context = [])
 * @method static Log alert(string $message, array $context = [])
 * @method static Log critical(string $message, array $context = [])
 * @method static Log error(string $message, array $context = [])
 * @method static Log warning(string $message, array $context = [])
 * @method static Log notice(string $message, array $context = [])
 * @method static Log info(string $message, array $context = [])
 * @method static Log debug(string $message, array $context = [])
 * @method static Log Logger()
 */
class Log extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'log';
    }
}
