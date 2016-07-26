<?php

namespace PAO;

use Illuminate\Container\Container;
use PAO\Routing\Route;


abstract class Controller
{
    /**
     * 全局容器
     * @var Container
     */
    protected $container;

    /**
     * [$controller 当前控制器]
     * @var [type]
     */
    protected $controller = null;

    /**
     * [$action 当前控制器方法]
     * @var [type]
     */
    protected $action = null;

    /**
     * [$var 预置公共变量]
     * @var array
     */
    protected $assign = [];

    /**
     * @var Route
     */
    protected $route;


    public function __construct()
    {
        $this->container = Container::getInstance();

        $this->route = $this->container->make('router')->route();

        list($this->controller, $this->action) = explode('@',$this->route->callable);

        $this->assign['ROUTE'] = $this->route;

        $this->assign['CONTROLLER'] = $this->controller;

        $this->assign['ACTION'] = $this->action;

        $this->assign($this->assign);
    }

    /**
     * make方法
     * @param $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        return $this->container->make($abstract, $parameters);
    }

    /**
     * [assign 模板动态变量附值]
     *
     * @param      $var
     * @param null $val
     *
     * @author  11
     * @version v1
     *
     */
    public function assign($var, $val = null)
    {
        $this->container->make('view')->assign($var, $val);
    }


    /**
     * [view]
     *
     * @param       $template
     * @param array $params
     *
     * @author  11
     * @version v1
     *
     */
    public function view($template, $variable = [])
    {
        return $this->container->make('view')->show($template, $variable);
    }
}
