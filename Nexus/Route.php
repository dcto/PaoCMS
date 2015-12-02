<?php

namespace PAO;

use Illuminate\Contracts\Container\Container;
use PAO\Exception\NotFoundHttpException;
use PAO\Http\Request;

class Route
{
    /**
     * 容器
     * @var
     */
    protected $container;

    /**
     * 已构建的路由数组
     * @var array
     */
    protected $routes = [];

    /**
     * 请求对象
     * @var array
     */
    protected $request = null;

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

    public function __construct(Container $container)
    {
        $this->container = $container;

        if (empty($this->routes)) {
            $this->_setRoutesByDefault();
        }
    }


    public function __call($method, $params)
    {

        array_push($this->routes, $params[0]);
        array_push($this->methods, strtoupper($method));
        array_push($this->callbacks, $params[1]);

    }

    public function dispatch()
    {
        $request = $this->container->DI('request');
        $pathInfo = $request->getPathInfo();
        $parameter = [];

        if (isset($this->routes[$pathInfo])) {
            $route = $this->routes[$pathInfo];
            $this->_getVerifyMethod($request, $route[0]);
            $this->callback = $route['to'];

        } else {
            foreach ($this->routes as $map => $route) {
                $pattern = strstr($map, ':');
                if ($pattern) {
                    $map = str_replace(array_keys($this->patterns), array_values($this->patterns), $map);
                    if (preg_match('#^' . $map . '$#', $pathInfo, $parameter)) {
                        array_shift($parameter);//remove the first parameter
                        $this->_getVerifyMethod($request, $route[0]);
                        $this->callback = $route['to'];
                        break;
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
        }

        try {
            return $this->container->call($this->_getCallBack(), $parameter);
        } catch (\Exception $e) {
            throw new NotFoundHttpException('The Target [' . implode('::'. $this->_getCallBack()) .']was error in the '. $pathInfo);
        }
    }

    /**
     * [_setRoutesByDefault initialization The Routing Map]
     *
     * @author  dc
     * @version v1
     *
     */
    protected function _setRoutesByDefault()
    {
        $this->routes = $this->container->config('route');
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
        if(!$this->callback) throw new NotFoundHttpException('The route was not found');

        //如果是自定义闭包
        if($this->callback instanceof \Closure) return $this->callback;

        //验证回调方法
        $this->_getIsSafeCallable($this->callback);

        list($controller, $function) = explode('@', $this->callback);
            $appController = $this->container->config('config.dir.controller');
        if(is_string($appController)) {
            $controller = APP . '\\' . $appController . '\\' . $controller;
        }else {
            $controller = APP.'\\Controller\\'.$controller;
        }

        //判断方法是否存在
        if (!method_exists($controller, $function)) {
            throw new NotFoundHttpException('The Target [' . $controller . '::' . $function . '] was not found');
        }
        return array($controller, $function);
    }
}


