<?php
/**
 * Router - routing urls to closures and controllers.
 *
 * @author PAO Framework
 * @version 3.0
 */

namespace PAO\Routing;

use Illuminate\Support\Arr;
use Illuminate\Container\Container;
use PAO\Exception\LogicException;
use PAO\Exception\SystemException;
use PAO\Exception\NotFoundHttpException;
use PAO\Http\Response;



/**
 * Router class will load requested Controller / Closure based on URL.
 */
class Router
{
    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * Matched Route, the current found Route, if any.
     *
     * @var Route|null $matchedRoute
     */
    protected $route;

    /**
     * Array of routes
     *
     * @var Route[] $routes
     */
    protected $routes = array();

    /**
     * @var array All available Filters
     */
    private $filters = array();

    /**
     * @var array
     * Retrieve the additional Routing Patterns from configuration.
     */
    private $regex = array(
        ':*'=>'.*',
        ':id'=>'\d+',
        ':any'=>'[^/]+',
        ':num'=>'[0-9]+',
        ':str'=>'[a-zA-Z]+',
        ':hex'=>'[a-f0-9]+',
        ':hash'=>'[a-z0-9]+',
        ':uuid'=>'[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
    );

    /**
     * Array of Route Groups
     *
     * @var array $groupStack
     */
    private $groupStack = array();


    /**
     * route namespace
     * @var null
     */
    private $namespace = null;

    /**
     * An array of HTTP request Methods.
     *
     * @var array $methods
     */
    public static $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'];

    /**
     * Router constructor.
     *
     * @codeCoverageIgnore
     */
    public function __construct(Container $container)
    {
        $this->container = $container ?: Container::getInstance();
    }


    /**
     * Register many request URIs to a single Callback.
     *
     * <code>
     *      // Register a group of URIs for a Callback
     *      Router::share(array(array('GET', '/'), array('POST', '/home')), 'App\Controllers\Home@index');
     * </code>
     *
     * @param  array  $routes
     * @param  mixed  $callback
     * @return void
     */
    public function share($routes, $callback)
    {
        foreach ($routes as $route) {
            $method = array_shift($route);
            $path  = array_shift($route);
            $this->register($method, $path, $callback);
        }
    }

    /**
     * Dispatch route
     * @return bool
     */
    public function dispatch()
    {
        $request = $this->container->make('request');

        // Get the Method and Path.
        $method = $request->method();

        $path = trim(urldecode($request->path()),'/');

        // Execute the Routes matching loop.
        foreach ($this->routes as $route) {
            //if ($route->match($path, $method, true, $this->regex)) {
            if ($this->matching($path, $method, $route)) {
                // Found a valid Route; process it.
                $this->route = $route;

                // Apply the (specified) Filters on matched Route.
                /*
                $result = $this->applyFiltersToRoute($route);

                if($result instanceof \Response) {
                    return $result;
                }*/
                return $this->ThroughRoute($route->callable, $route->parameters);
            }
        }
        // No valid Route found; send an Error 404 NotFoundHttpException Response.
        throw new NotFoundHttpException('Can\'t found route of the url: '. $request->baseUrl().'/'.htmlspecialchars($path, ENT_COMPAT, 'ISO-8859-1', true));
    }


    /**
     * 匹配路由
     * @param $path
     * @param $method
     * @param $route
     * @return bool
     */
    private function matching($path, $method, $route)
    {
            if (!in_array($method, $route->methods)) return false;

            $route->method = $method;

            $route->path = $path;

            $pattern = $route->pattern;

            if($pattern == $path) return true;

            if (strpos($pattern, ':')) {
                $pattern = str_replace(array_keys($this->regex), array_values($this->regex), $pattern);
            }

            $regex = str_replace(array('(/', ')'), array('(?:/', ')?'), $pattern);
            $route->regex = $regex;
            //if (preg_match($reg = '#^(?:([a-z]{2})?/?)?'.$pattern.'(?:\?.*)?$#', $path, $matches)) {
            if (preg_match('#^'.$regex.'$#', $route->path, $matches)) {
                array_shift($matches);
                $route->parameters = $matches;
                return true;
            }

        return false;
    }


    private function compilerRoute($route)
    {
        $path = explode('/', $route->getPattern());

        $params = array();

        $path = array_map(function($p) use ($route){
                if(strpos($p, ':')){
                    //parse_str(strtr($p, ':|', '=&'), $params);
                    //$route->setParameters($params);
                    list($key, $val) = explode(':', $p);

                    if(isset($this->regex[$key])){
                        return  $this->regex[$key];
                    }
                }

            return $p;
        },$path);
    }

    /**
     * Defines a route with or without Callback and Method.
     *
     * @param string $method
     * @param array @params
     */

    public function __call($method, $params)
    {
        $method = strtoupper($method);
        if (($method != 'ANY') && !in_array($method, static::$methods)) {
            throw new NotFoundHttpException('Invalid Method Of The Router');
        }

        // Get the Route.
        $route = array_shift($params);

        if (!$route || !$params) {
            throw new NotFoundHttpException('Invalid Parameter Of The Router');
        }

        $action = array_shift($params);

        // Register the Route.
        return $this->register($method, $route, $action);
    }


    /**
     * global pattern
     * @param $key
     * @param $regex
     */
    public function regex($key, $regex)
    {
        $this->regex[$key] = $regex;
    }


    /**
     * Return current route
     *
     * @return array
     */
    public function route()
    {
        return $this->route;
    }


    /**
     * Return the available Routes.
     *
     * @return array
     */
    public function routes()
    {
        return $this->routes;
    }


    /**
     * Defines a Route Group.
     *
     * @param string $group The scope of the current Routes Group
     * @param callback $callback Callback object called to define the Routes.
     */
    public function group($attributes, $callback)
    {
        if ($this->groupStack) {
            $attributes = $this->mergeGroup($attributes, end($this->groupStack));
        }
        $this->groupStack[] = array_map('trim',$attributes);

        // Call the Callback, to define the Routes on the current Group.
        call_user_func($callback);

        // Removes the last Route Group from the array.
        array_pop($this->groupStack);
    }



    /* The Resourceful Routes in the Laravel Style.

    Method     |  Path                 |  Action   |
    ------------------------------------------------
    GET        |  /photo               |  index    |
    GET        |  /photo/create        |  create   |
    POST       |  /photo               |  store    |
    GET        |  /photo/{photo}       |  show     |
    GET        |  /photo/{photo}/edit  |  edit     |
    PUT/PATCH  |  /photo/{photo}       |  update   |
    DELETE     |  /photo/{photo}       |  destroy  |

    */

    /**
     * Defines a Resourceful Routes Group to a target Controller.
     *
     * @param string $basePath The base path of the resourceful routes group
     * @param string $controller The target Resourceful Controller's name.
     */
    public function resource($basePath, $controller)
    {
        $this->register('get',                 $basePath,                 $controller .'@index');
        $this->register('get',                 $basePath .'/create',      $controller .'@create');
        $this->register('post',                $basePath,                 $controller .'@insert');
        $this->register('get',                 $basePath .'/(:any)',      $controller .'@show');
        $this->register('get',                 $basePath .'/(:any)/modify', $controller .'@modify');
        $this->register(array('put', 'patch'), $basePath .'/(:any)',      $controller .'@update');
        $this->register('delete',              $basePath .'/(:any)',      $controller .'@delete');

    }

    /**
     * resource alias name
     * @param $bassPath
     * @param $controller
     */
    public function auto($bassPath, $controller)
    {
         $this->resource($bassPath, $controller);
    }

    /**
     * Define a Route Filter.
     *
     * @param string $name
     * @param callback $callback
     */
    public function filter($name, $callback)
    {
        if (array_key_exists($name, $this->filters)) {
            throw new \Exception('Filter already exists: ' .$name);
        }

        $this->filters[$name] = $callback;
    }


    /**
     * ThroughRoute
     * @param $callback
     * @param array $parameter
     * @return mixed
     */
    protected function ThroughRoute($callback, $parameters = [])
    {
        if($callback instanceof Response){
            return $callback;
        }

        if($callback instanceof  \Closure){
            return call_user_func($callback, $parameters);
        }

        if(strpos($callback, '@')){
            return $this->container->call($callback, $parameters);
        }

        throw new NotFoundHttpException('Invalid Route Target '.$callback. ' in '.static::$route->path() .' Of Your Route');
    }

    /**
     * Maps a Method and URL pattern to a Callback.
     *
     * @param string $method HTTP method(s) to match
     * @param string $route URL pattern to match
     * @param callback $callback Callback object
     */
    protected function register($method, $path, $property)
    {

        // Prepare the route Methods.
        if (is_string($method) && (strtolower($method) == 'any')) {
            $methods = static::$methods;
        } else {
            $methods = array_map('strtoupper', (array) $method);
            // Ensure the requested Methods are valid ones.
            $methods = array_intersect($methods, static::$methods);
        }

        if(!$property){
            throw new SystemException('Invalid route parameter of the '.$path);
        }

        // Pre-process the Action information.
        $property = $this->parseAction($property);

        if ($this->groupStack) {
            $prefix = array();
            $namespace = $language = null;
            foreach ($this->groupStack as $group) {
                // Add the current prefix to the prefix list.
                array_push($prefix, trim($group['prefix'], '/'));
                $namespace = Arr::get($group, 'namespace');
                $language = Arr::get($group, 'lang');
            }

            $prefix && $property['prefix'] = implode('/', $prefix);


            if(!isset($property['lang']) && $language) {
                $property['lang'] = $language;
            }

            // Adjust the Route callback, if it is needed.
            if($namespace && is_string($property['to'])) {
                if(strpos($property['to'],'@')){
                    $property['to'] = rtrim($namespace,'\\') .'\\' .ltrim($property['to'], '\\');
                }
            }

            $property['group'] = end($this->groupStack);
        }

       return $this->addPushToRoutes(new Route($methods, $path, $property));
    }


    /**
     * addPushRoute
     * @param $route
     */
    protected function addPushToRoutes(Route $route)
    {
        $hash = $route->hash = $route->alias;
        if($hash && isset($this->routes[$hash])){
            throw new SystemException("The route name [$hash] was exist");
        }
        if($hash){
           return $this->routes[$hash] = $route;
        }
        return $this->routes[] = $route;
    }


    /**
     * applyFiltersToRoute
     * @param \PAO\Routing\Route $route
     * @return mixed|null
     * @throws \Exception
     */
    protected function applyFiltersToRoute(Route $route)
    {
        $result = null;

        foreach ($route->getFilters() as $filter => $params) {
            if(empty($filter)) {
                continue;
            } else if (! array_key_exists($filter, $this->filters)) {
                throw new LogicException('Invalid Filter specified: ' .$filter);
            }

            // Get the current Filter Callback.
            $callback = $this->filters[$filter];

            // If the Callback returns a Response instance, the Filtering will be stopped.
            if (is_callable($callback)) {
                $result = call_user_func($callback, $route, $params);
            }

            if ($result instanceof Response) {
                break;
            }
        }

        return $result;
    }


    /**
     * Parse the Route Action into a standard array.
     *
     * @param  \Closure|array  $property
     * @return array
     */
    protected function parseAction($property)
    {
        if (is_string($property) || is_callable($property)) {
            // A string or Closure is given as Action.
            return array('to' => $property);
        } else if(!isset($property['to'])) {
            // Find the Closure in the Action array.
            $property['to'] = $this->findClosure($property);
        }
        return $property;
    }

    /**
     * Find the Closure in an property array.
     *
     * @param  array  $property
     * @return \Closure
     */
    protected function findClosure(array $property)
    {
        return array_first($property, function($key, $value)
        {
            return is_callable($value);
        });
    }

    /**
     * Merge the given group attributes.
     *
     * @param  array  $new
     * @param  array  $old
     * @return array
     */
    protected static function mergeGroup($new, $old)
    {
        $new['namespace'] = static::formatUsesPrefix($new, $old);

        $new['prefix'] = static::formatGroupPrefix($new, $old);

        if (isset($new['domain'])) {
            unset($old['domain']);
        }

        $new['where'] = array_merge(
            isset($old['where']) ? $old['where'] : [],
            isset($new['where']) ? $new['where'] : []
        );

        if (isset($old['as'])) {
            $new['as'] = $old['as'].(isset($new['as']) ? $new['as'] : '');
        }

        return array_merge_recursive(Arr::except($old, ['namespace', 'prefix', 'append', 'where', 'as']), $new);
    }

    /**
     * Prepend the last group uses onto the use clause.
     *
     * @param  string  $uses
     * @return string
     */
    protected function prependGroupUses($uses)
    {
        $group = end($this->groupStack);

        return isset($group['namespace']) && strpos($uses, '\\') !== 0 ? $group['namespace'].'\\'.$uses : $uses;
    }

    /**
     * Format the uses prefix for the new group attributes.
     *
     * @param  array  $new
     * @param  array  $old
     * @return string|null
     */
    protected static function formatUsesPrefix($new, $old)
    {
        if (isset($new['namespace'])) {
            return isset($old['namespace'])
                ? trim($old['namespace'], '\\').'\\'.trim($new['namespace'], '\\')
                : trim($new['namespace'], '\\');
        }

        return isset($old['namespace']) ? $old['namespace'] : null;
    }


    /**
     * Format the prefix for the new group attributes.
     *
     * @param  array  $new
     * @param  array  $old
     * @return string|null
     */
    protected static function formatGroupPrefix($new, $old)
    {
        $oldPrefix = isset($old['prefix']) ? $old['prefix'] : null;

        if (isset($new['prefix'])) {
            return trim($oldPrefix, '/').'/'.trim($new['prefix'], '/');
        }

        return $oldPrefix;
    }

    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    public function parametersWithoutNulls()
    {
        return array_filter($this->parameters(), function ($p) {
            return ! is_null($p);
        });
    }


    /**
     * Get the key / value list of parameters for the route.
     *
     * @return array
     *
     * @throws \LogicException
     */
    public function parameters()
    {
        if (isset($this->parameters)) {
            return array_map(function ($value) {
                return is_string($value) ? rawurldecode($value) : $value;
            }, $this->parameters);
        }

        throw new LogicException('Route is not bound.');
    }

}
