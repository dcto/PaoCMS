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
     * @author  11
     * @version v1
     *
     */
    public function view($template, $variable = [])
    {
        $template = $template . $this->container->config('template.suffix');
        $variable = array_merge($this->variable, $variable);
        $twig = $this->container->DI('view')->twig();

        try {
            return new Response($twig->render($template, $variable, true));
        }catch (NotFoundHttpException $e){
            throw new NotFoundHttpException($e->getMessage());
        }
    }
}