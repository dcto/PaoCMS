<?php

namespace PAO;

use Illuminate\Container\Container;
use PAO\Exception\NotFoundHttpException;
use PAO\Http\Response;


class Controller
{
    /**
     * 容器
     * @var Container
     */
    public $container;


    /**
     * 模板公共预定义变量
     * @var array
     */
    public $variable = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function assign($var, $val = null)
    {
        if(is_array($var))
        {
            foreach($var as $key => $v)
            {
                $this->variable[$key] = $v;
            }
        }else{
            $this->variable[$var] = $val;
        }
    }


    /**
     * [view]
     *
     * @param       $template
     * @param array $params
     *
     * @author  i@pao11.com
     * @version v1
     *
     */
    public function view($template, $variable = [])
    {

        $template = $template . $this->container->config('template.suffix');

        $twig = $this->container->DI('view')->twig();

        $variable = array_merge($this->variable, $variable);

        return new Response($twig->render($template, $variable, true));
    }


}