<?php

namespace PAO;

use PAO\Http\Request;
use Illuminate\Container\Container;
use PAO\Exception\NotFoundHttpException;


class Route
{
    /**
     * 容器
     * @var
     */
    protected $container;

    /**
     * 当前请求控制器
     * @var [type]
     */
    protected $controller = null;

    /**
     * 当前请求方法
     * @var [type]
     */
    protected $action = null;

    /**
     * 已构建的路由数组
     * @var array
     */
    protected $routes = null;

    /**
     * 路由对应的控制器回调
     * @var array
     */
    protected $callback = null;

    /**
     * 预定义正则
     * @var array
     */
    protected $patterns = [
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':str' => '[a-zA-Z]+',
        ':all' => '.*'
    ];

    public function __construct()
    {
        $this->container = Container::getInstance();
        $this->routes();
    }


    public function __call($method, $params)
    {
        $route = $params[0];
        array_push($route[key($route)], $method);
        $this->routes($route);
    }

    /**
     * [get get route path by alias]
     * @param  [type] $alias [description]
     * @return [type]        [description]
     */
    public function get($alias, $parameters = null)
    {
        foreach ( (array) $this->routes as $path => $route) {
            if($route['as'] == $alias)
            {
                if(!is_array($parameters)) return $path;
                return preg_replace_callback('/:\w+/', function($matches) use (&$parameters) {
                    return array_shift($parameters);
                }, str_replace(array('(',')'),'',$path));
            }
        }
        throw new NotFoundHttpException("The alias [$alias] route was not found!");
    }


    /**
     * [get the route config]
     * @return mixed
     */
    public function config()
    {
        return (array) $this->container->config('route');
    }

    /**
     * [routes init format routes]
     * @return array
     */
    public function routes($route = array())
    {
        $config = $this->config();
        $routes =array_merge($route);
        array_walk($config, function($value, $group) use (&$routes){
            $route = isset($value['route']) ? $value['route'] : $value;
            $routes = array_merge($routes, $route);
        });

        return $this->routes = $routes;
    }

    /**
     * [getController get Current Controller]
     * @return [string]
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * [getAction get Current Action]
     * @return [string]
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * [dispatch route dispatch]
     * @return callback;
     */
    public function dispatch()
    {
        $request = $this->container->make('request');
        $parameter = [];

        if (isset($this->routes[$request->path()])) {
            $route = $this->routes[$request->path()];
            $this->_getVerifyMethod($request, $route[0]);
            $this->callback = isset($route['to'])? $route['to'] : null;
        } else {
            foreach ($this->routes as $map => $route) {
                $pattern = strstr($map, ':');
                if ($pattern) {
                    $map = str_replace(array_keys($this->patterns), array_values($this->patterns), $map);
                    if (preg_match('#^' . $map . '$#', $request->path(), $parameter)) {
                        array_shift($parameter);//remove the first parameter
                        $this->_getVerifyMethod($request, $route[0]);
                        $this->callback = isset($route['to'])? $route['to'] : null;
                        break;
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
        }
            return $this->container->call($this->_getCallBack(), $parameter);
    }

    protected function _getIsSafeCallable($callback)
    {
        if (strpos($callback, '@') == false) {
            throw new NotFoundHttpException('Please make sure the route include symbol "@" in the route config key "to" ' . $callback);
        }
        return true;
    }

    protected function _getVerifyMethod(Request $request, $method)
    {
        $method = strtoupper($method);
        if ($method != $request->getMethod() && $method != 'ANY' && $method != 'ALL') throw new NotFoundHttpException('The route method was not available ' . $request->getUri());
    }

    protected function _getCallBack()
    {
        if(!$this->callback) throw new NotFoundHttpException('The route was not found check your pathInfo Please!');

        //如果是自定义闭包
        if($this->callback instanceof \Closure) return $this->callback;

        //验证回调方法
        $this->_getIsSafeCallable($this->callback);
        list($controller, $action) = explode('@', $this->callback);
        if(!$this->callback || $action=='='){
            list($controller, $action) = explode('/', trim($this->container->make('request')->path(),'/'));
        }

        $this->controller = class_basename($controller);
        $this->action = $action;
        $appController = $this->container->config('config.dir.controller');
        if(is_string($appController)) {
            $controller = basename(APP).'\\'.$appController.'\\'.$controller;
        }else {
            $controller = basename(APP).'\\Controller\\'.$controller;
        }

        //判断方法是否存在并将其实例化
        if (!method_exists($instance = $this->container->make($controller), $action)) {
           throw new NotFoundHttpException  ('The target [' . $controller . '::' . $action . '] does not exist!');

        }

        return array($instance, $action);
    }
}


