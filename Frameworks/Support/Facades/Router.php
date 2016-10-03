<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Router
 * @method static Router group(array $attributes, Closure $callback)
 * @method static Router any(string $url, array $params)
 * @method static Router get(string $url, array $params)
 * @method static Router post(string $url, array $params)
 * @method static Router put(string $url, array $params)
 * @method static Router patch(string $url, array $params)
 * @method static Router delete(string $url, array $params)
 * @method static Router head(string $url, array $params)
 * @method static Router options(string $url, array $params)
 * @method static Router share(array $routes)
 * ------------------------------------------------
 * GET        |  /test               |  index    |
 * GET        |  /test/(:id)         |  select   |
 * GET        |  /test/create        |  create   |
 * POST       |  /test               |  insert   |
 * GET        |  /test/(:id)/modify  |  modify   |
 * PUT/PATCH  |  /test/(:id)         |  update   |
 * DELETE     |  /test/(:id)         |  delete   |
 * @method static Router resource(string $url, string $controller)
 * @method static Router restful(string $url, string $controller)
 * @method static Router regex(string $key, string $regex)
 * @method static Router router(string $tag)
 * @method static Router routes(string $tag)
 * @method static Router groups()
 * @method static Router dispatch()
 */
class Router extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'router';
    }
}
