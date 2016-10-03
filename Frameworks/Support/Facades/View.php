<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class View
 *
 * @method static View twig()
 * @method static View show(string $template, array $variables)
 * @method static View assign(string $var, mixed $val = null)
 * @method static View render(string $template, array $variables)
 */
class View extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'view';
    }
}
