<?php

namespace PAO\Support\Facades;
/**
 * @see \Illuminate\Contracts\Routing\ResponseFactory
 */
class Response extends \Illuminate\Support\Facades\Response
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'response';
    }
}
