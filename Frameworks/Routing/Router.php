<?php
/**
 * Router - routing urls to closures and controllers.
 *
 * @author PAO Framework
 * @version 3.0
 */

namespace PAO\Routing;

use Arr;
use PAO\Http\Request;
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
     * @var Route object $matched Route
     */
    private $router;

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
     * @var array router property
     */
    private $property = array();

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
        if (($method == 'any') || in_array(strtoupper($method), static::$methods)) {
            // Get the Route.
            $route = array_shift($params);
            if (!$route || !$params) {
                throw new SystemException('Invalid Parameter Of The Router');
            }
            $property = array_shift($params);

            // Register the Route.
           return $this->register($method, $route, $property);
        }else{
            throw new SystemException('Invalid Parameter Of The Method');
        }
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
        if($regex){
            $this->regex[$key] = $regex;
        }else if($key){
            return $this->regex[$key];
        }else{
            return $this->regex;
        }
    }

    /**
     * return route object default return current route
     * @param null $tag
     * @return array|object|Route
     */
    public function router($tag = null)
    {
        if($tag){
             if(isset($this->routes[$tag])){
                 return $this->routes[$tag];
             }else{
                 foreach($this->routes as $router) {
                     if($router->tag == $tag) {
                         return $router;
                     }
                 }
             }
            throw new NotFoundHttpException('Can not found the ['.$tag. '] router.');
        }
        if(!$this->router){
            throw new NotFoundHttpException('Current route can not available.');
        }
        return $this->router;
    }


    /**
     * return routes array access current params route name
     * @return array
     */
    public function routes()
    {
        $tags = func_get_args();
        $routes = array();
        foreach ($this->routes as $tag => $route) {
            if($tags && !in_array($tag, $tags, true)) continue;
                $routes[$tag] = $route;
        }
        return $routes;
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
    public function groups()
    {
        $tags = func_get_args();
        $groups = array();
        foreach ($this->group as $tag => $group) {
            if($tags && !in_array($tag, $tags)) continue;
            $groups[$tag] = $group;
            unset($groups[Arr::get($group, 'pid')]);
            foreach ($this->routes as $route){
                $route->group == $tag && $groups[$tag]['routes'][] = $route;
            }
        }
        return $groups;
    }

    /**
     * dispatch to the router
     * @return mixed
     */
    public function dispatch(Request $request, Response $response)
    {
        // Get the Method and Path.
        $url = trim(urldecode($request->path()));
        $method = $request->method();

        // Execute the Routes matching loop.
        foreach ($this->routes as $router) {
            if ($this->Matching($url, $method, $router)) {
                // Found a valid Route; process it.
                $router->url = $url;
                $router->method = $method;
                $this->router = $router;

                if($router->group) {
                    if (!$group = Arr::get($this->group, $router->group)) {
                        throw new NotFoundHttpException('Does not define ' . $router->group . ' of router group');
                    }

                    /**
                     * construct callback
                     */
                    if ($callback = Arr::get($group, 'call')) {
                        if (is_array($callback)) {
                            $callback = $this->Fire(array_shift($callback), array_shift($callback));
                        } else {
                            $callback = $this->Fire($callback);
                        }
                        if ($callback instanceof Response) {
                            return $callback;
                        }
                    }
                }
                /**
                 * construct instance
                 */
                $instance = $this->Fire($router->getCallable(), $router->parameters);

                if(is_string($instance)){
                    return $response->make($instance);
                }else{
                    return $instance;
                }
            }
        }
        // No valid Route found; send an Error 404 NotFoundHttpException Response.
        throw new NotFoundHttpException("Can not match route of path '$url' from current url: ". $request->url());
    }

    /**
     * 匹配路由
     * @param $path
     * @param $method
     * @param $route
     * @return bool
     */
    private function Matching($url, $method, $router)
    {
        if (!in_array($method, $router->methods)) return false;
        if($router->route == $url) return true;
        /**
        if (strpos($pattern, ':')) {
            $pattern = str_replace(array_keys($this->regex), array_values($this->regex), $pattern);
        }
        $regex = str_replace(array('(/', ')'), array('(?:/', ')?'), $router->regex);
         */
        //if (preg_match($reg = '#^(?:([a-z]{2})?/?)?'.$pattern.'(?:\?.*)?$#', $path, $matches)) {
        if (preg_match('#^'.$router->regex.'$#i', $url, $matches)) {
            array_shift($matches);
            $router->parameters = $this->parameters($matches);
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
    protected function Fire($callback, $parameters = [])
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
        throw new NotFoundHttpException("Invalid Route Target [$callback] in {$this->router->route} Of Your Route");
    }

    /**
     * Maps a Method and URL pattern to a Callback.
     *
     * @param string $method HTTP method(s) to match
     * @param string $route URL pattern to match
     * @param callback $callback Callback object
     */
    protected function register($method, $route, $property)
    {
        //Merge the property
        //$property = array_replace_recursive($property, $this->property);

        //Prepare the route Methods.
        if (is_string($method) && (strtolower($method) == 'any')) {
            $methods = static::$methods;
        } else {
            $methods = array_map('strtoupper', (array) $method);
            // Ensure the requested Methods are valid ones.
            $methods = array_intersect($methods, static::$methods);
        }

        if(!$property){
            throw new SystemException('Invalid route parameter of the '.$route);
        }

        // Pre-process the Action information.
        $property = $this->parseAction($property);

        if ($this->group) {
            $property['group'] = end($this->group);
        }

        $route = $this->parseRoute($route, $property);

        $property['regex'] = str_replace(array_keys($this->regex), array_values($this->regex), $route);

        return $this->addPushToRoutes($this->router = new Route($methods, $route, $property));
    }


    /**
     * addPushRoute
     * @param $route
     */
    protected function addPushToRoutes(Route $router)
    {
        if($tag = $router->tag){
            if(isset($this->routes[$tag])){
                throw new SystemException("The route name [$tag] exist");
            }
           return $this->routes[$tag] = $router;
        }
        return $this->routes[] = $router;
    }


    /**
     * parse pattern of the route path
     * @param $pattern
     * @param $property
     * @return string
     */
    private function parseRoute($route, $property)
    {
        $prefix = Arr::get($property,'prefix') ?: Arr::get(Arr::get($property,'group'),'prefix');
        $route = '/'.trim(trim($prefix,'/').'/'.trim($route, '/'),'/');
        return $route;
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
            return array('call' => $property);
        } else if(!isset($property['call'])) {
            // Find the Closure in the Action array.
            $property['call'] = $this->findClosure($property);
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
            //unset($this->group[$attributes['pid']]);
        }
        $tag = $attributes['tag'] = Arr::get($attributes,'tag', crc32(serialize($attributes)));
        /*
        if(isset($this->group[$tag])){
            throw new SystemException('The Route Group exist');
        }
        */

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
}
