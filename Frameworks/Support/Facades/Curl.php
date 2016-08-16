<?php

namespace PAO\Support\Facades;

/**
 * Class Curl
 * @see PAO\Http\Curl
 * @package PAO\Support\Facades
 */
class Curl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'curl';
    }
}
