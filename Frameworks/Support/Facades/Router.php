<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Router
 * @method static \PAO\Routing\Router group(array $attributes, Closure $callback)
 * @method static \PAO\Routing\Router get(string $url, array $params)
 * @method static \PAO\Routing\Router post(string $url, array $params)
 * @method static \PAO\Routing\Router put(string $url, array $params)
 * @method static \PAO\Routing\Router patch(string $url, array $params)
 * @method static \PAO\Routing\Router delete(string $url, array $params)
 * @method static \PAO\Routing\Router head(string $url, array $params)
 * @method static \PAO\Routing\Router options(string $url, array $params)
 * @method static \PAO\Routing\Router share(array $routes)
 * ------------------------------------------------
 * GET        |  /test               |  index    |
 * GET        |  /test/(:id)         |  select   |
 * GET        |  /test/create        |  create   |
 * POST       |  /test               |  insert   |
 * GET        |  /test/(:id)/modify  |  modify   |
 * PUT/PATCH  |  /test/(:id)         |  update   |
 * DELETE     |  /test/(:id)         |  delete   |
 * @method static \PAO\Routing\Router resource(string $url, string $controller)
 * @method static \PAO\Routing\Router restful(string $url, string $controller)
 * @method static \PAO\Routing\Router regex(string $key, string $regex)
 * @method static \PAO\Routing\Router router(string $tag)
 * @method static \PAO\Routing\Router routes(string $tag)
 * @method static \PAO\Routing\Router groups()
 * @method static \PAO\Routing\Router dispatch()
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
