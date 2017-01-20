<?php

namespace PAO;


abstract class Controller
{
    /**
     * 全局容器
     * @var Application
     */
    protected $app;

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
     * [$route 当前路由]
     * @var object
     */
    protected $router = null;

    /**
     * [$var 预置公共变量]
     * @var array
     */
    protected $assign = [];


    public function __construct()
    {
        $this->app = app();

        $this->router = $this->app->make('router')->router();

        $this->controller = $this->router->getController();

        $this->action = $this->router->getAction();

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
        return $this->app->make($abstract, $parameters);
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
        $this->app->make('view')->assign($var, $val);
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
        return $this->app->make('view')->show($template, $variable);
    }
}
