<?php
/**
 * Router - routing urls to closures and controllers.
 *
 * @author PAO Framework
 * @version 3.0
 */

namespace PAO\Routing;

use PAO\Support\Arr;
use PAO\Http\Response;
use PAO\Exception\SystemException;
use PAO\Exception\NotFoundHttpException;
use Illuminate\Container\Container;


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
    private $container;


    /*
     * @var $group
     */
    private $group = array();

    /**
     * Matched Route, the current found Route, if any.
     *
     * @var $route object $matched Route
     */
    private $route;

    /**
     * Array of routes
     *
     * @var $routes Route[] $routes
     */
    private $routes = array();

    /**
     * An array of HTTP request Methods.
     *
     * @var array $methods
     */
    private static $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'];


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
     * Router constructor.
     *
     * @codeCoverageIgnore
     */
    public function __construct(Container $container)
    {
        $this->container = $container ?: Container::getInstance();
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
     * Dispatch route
     * @return bool
     */
    public function dispatch()
    {
        $request = $this->container->make('request');

        // Get the Method and Path.
        $method = $request->method();

        $path = '/'.trim(urldecode($request->path()),'/');
        // Execute the Routes matching loop.
        foreach ($this->routes as $route) {
            if ($this->matching($path, $method, $route)) {
                // Found a valid Route; process it.
                $this->route = $route;
                if(!$group = Arr::get($this->group,$route->group)){
                    throw new NotFoundHttpException('The can not define '.$route->group. ' of router group');
                }
                if($callback = Arr::get($group,'call')){
                    if(is_array($callback)) {
                        $this->ThroughRoute(array_shift($callback), array_shift($callback));
                    }else{
                        $this->ThroughRoute($callback);
                    }
                }
                return $this->ThroughRoute($route->getCallable(), $route->parameters);
            }
        }
        // No valid Route found; send an Error 404 NotFoundHttpException Response.
        throw new NotFoundHttpException("Can not match route of path '$path' from current url: ". $request->url());
    }


    /*
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
    */


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
        foreach($routes as $route) {
            $method = array_shift($route);
            $path  = array_shift($route);
            $this->register($method, $path, $callback);
        }
    }



    /* The Resourceful Routes in the Laravel Style.

    Method     |  Path                 |  Action   |
    ------------------------------------------------
    GET        |  /test               |  index    |
    GET        |  /test/(:id)         |  select   |
    GET        |  /test/create        |  create   |
    POST       |  /test               |  insert   |
    GET        |  /test/(:id)/modify  |  modify   |
    PUT/PATCH  |  /test/(:id)         |  update   |
    DELETE     |  /test/(:id)         |  delete   |

    */

    /**
     * Defines a Resourceful Routes Group to a target Controller.c
     *
     * @param string $basePath The base path of the resourceful routes group
     * @param string $controller The target Resourceful Controller's name.
     */
    public function resource($basePath, $controller)
    {
        $this->register('get',                 $basePath,                 $controller .'@index');
        $this->register('get',                 $basePath .'/(:any)',      $controller .'@select');
        $this->register('get',                 $basePath .'/create',      $controller .'@create');
        $this->register('post',                $basePath,                 $controller .'@insert');
        $this->register('get',                 $basePath .'/(:any)/modify', $controller .'@modify');
        $this->register(array('put', 'patch'), $basePath .'/(:any)',      $controller .'@update');
        $this->register('delete',              $basePath .'/(:any)',      $controller .'@delete');

    }

    /**
     * resource alias name
     * @param $bassPath
     * @param $controller
     */
    public function restful($bassPath, $controller)
    {
        $this->resource($bassPath, $controller);
    }

    /**
     * global pattern
     * @param $key
     * @param $regex
     */
    public function regex($key = null, $regex = null)
    {
        $this->regex[$key] = $regex;
    }


    /**
     * Return current route
     *
     * @return object
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
    public function routes($group = null)
    {
        if($group){
            $routes = array();
            foreach ($this->routes as $route) {
                if($route->group == $group){
                    $route->tag ? $routes[$route->tag] = $route : $routes[] = $route;
                }
            }
            return $routes;
        }
        return $this->routes;
    }

    /**
     * Create a route group with shared attributes.
     *
     * @param  array  $attributes
     * @param  \Closure  $callback
     * @return void
     */
    public function group(array $attributes, \Closure $callback)
    {
        //array_push($this->groupStack, $attributes);
        $this->updateGroupStack($attributes);
        //$this->groupStack[] = $attributes;//array_merge_recursive($this->groupStack, $attributes);
        // Once we have updated the group stack, we will execute the user Closure and
        // merge in the groups attributes when the route is created. After we have
        // run the callback, we will pop the attributes off of this group stack.
        call_user_func($callback, $this);

        array_pop($this->groupStack);
    }

    /**
     * format the route item list
     * @param null $key
     * @return array
     */
    public function groups($tag = null)
    {
        $routes = array();
        foreach ($this->routes as $route){
            if($tag && Arr::get($route->group, 'tag') == $tag){
                $routes[$tag][] = $route;
            }else{
                $routes[Arr::get($route->group, 'tag')][] = $route;
            }
        }
        return $this->group;
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
            $route->parameters = $this->parameters($matches);
            return true;
        }
        return false;
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

        if ($this->group) {
            $property['group'] = end($this->group);
        }

       return $this->addPushToRoutes(new Route($methods, $path, $property));
    }


    /**
     * addPushRoute
     * @param $route
     */
    protected function addPushToRoutes(Route $route)
    {
        $tag = $route->tag;
        if($tag && isset($this->routes[$tag])){
            throw new SystemException("The route name [$tag] was exist");
        }
        if($tag){
           return $this->routes[$tag] = $route;
        }
        return $this->routes[] = $route;
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
     * Find the Closure in an action array.
     *
     * @param  array  $action
     * @return \Closure
     */
    protected function findClosure(array $action)
    {
        return array_first($action, function($key, $value)
        {
            return is_callable($value);
        });
    }
    /**
     * Update the group stack with the given attributes.
     *
     * @param  array  $attributes
     * @return void
     */
    protected function updateGroupStack(array $attributes)
    {
        if (! empty($this->groupStack)) {
            $attributes = $this->mergeGroup($attributes, end($this->groupStack));
            $attributes['pid'] = Arr::get(end($this->groupStack),'tag');
            unset($this->group[$attributes['pid']]);
        }
        $tag = $attributes['tag'] = Arr::get($attributes,'tag', crc32(serialize($attributes)));
        if(isset($this->group[$tag])){
            throw new SystemException('The Route Group exist');
        }

        $this->group[$tag] = $this->groupStack[] = $attributes;
    }

    /**
     * Merge the given group attributes.
     *
     * @param  array  $new
     * @param  array  $old
     * @return array
     */
    private function mergeGroup($new, $old)
    {
        $new['namespace'] = static::formatUsesPrefix($new, $old);

        $new['prefix'] = static::formatGroupPrefix($new, $old);

        return array_replace_recursive(Arr::except($old, ['namespace', 'prefix']), $new);
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
     * Get the prefix from the last group on the stack.
     *
     * @return string
     */
    private function getLastGroupPrefix()
    {
        if (! empty($this->groupStack)) {
            $last = end($this->groupStack);

            return isset($last['prefix']) ? $last['prefix'] : '';
        }

        return '';
    }


    private function parameters($parameters)
    {
        if ($parameters) {
            return array_map(function ($value) {
                return is_string($value) ? rawurldecode($value) : $value;
            }, $parameters);
        }
        return array();
    }

}
