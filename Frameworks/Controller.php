<?php

namespace PAO;

use Illuminate\Container\Container;


class Controller
{
    /**
     * 容器
     * @var Container
     */
    public $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
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