<?php

namespace PAO;

use Illuminate\Container\Container;


class Controller
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


    public function __construct(Container $container)
    {

        $this->container = $container;

        $this->controller = $this->container->make('route')->getController();

        $this->action = $this->container->make('route')->getAction();

        $this->assign($this->assign);
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
