<?php

namespace PAO;

use Illuminate\Container\Container;
use PAO\Exception\NotFoundHttpException;



class View
{
    /**
     * 容器
     * @var Container
     */
    protected $container;


    /**
     * 模板公共变量
     * @var array
     */
    public $variable = [];

    /**
     * 模板路径
     * @var
     */
    protected $views;

    /**
     * 模板缓存路径
     * @var
     */
    protected $cache;


    public function __construct()
    {
        $this->container = Container::getInstance();
        $view = $this->container->config('config.dir.view');
        $this->views = $view ? PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.$view : PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'View';
        $this->cache = $this->container->config('template.dir.cache');
        $this->debug =  $this->container->config('config.debug');


    }


    /**
     * [twig 模板引擎]
     *
     * @return \Twig_Environment
     * @author  11
     * @version v1
     *
     */
    public function twig()
    {
        $loader = new \Twig_Loader_Filesystem($this->views);
        /*example
        $loader->addPath($templateDir3);
        $loader->prependPath($templateDir4);
        */
        $twig  = new \Twig_Environment($loader, array(
            'cache' => $this->cache,
            'debug' => true,
            'strict_variables' =>true
        ));
        /*
        $lexer = new \Twig_Lexer($twig, array(
            'tag_comment' => array('{#', '#}'),
            'tag_block' => array('{%', '%}'),
            'tag_variable' => array('{^', '^}'),
            'interpolation' => array('#{', '}'),
        ));
        $twig->setLexer($lexer);
        */

        $twig->addGlobal('PAO', PAO);
        $twig->addGlobal('APP', APP);

        $url = new \Twig_SimpleFunction('URL', array($this->container->make('request'), 'getUri'));
        $twig->addFunction($url);

        return $twig;
    }


    /**
     * [assign 模板变量赋值方法]
     *
     * @param      $var [变量名]
     * @param null $val [变量值]
     * @author 11.
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
     * [render 模板渲染]
     *
     * @param $template
     * @param $variable
     * @return string
     * @author 11.
     */
    public function render($template, $variable)
    {
        $template = $template . $this->container->config('template.suffix');

        $variable = array_merge($this->variable, $variable);
        try {
            return $this->twig()->render($template, $variable);
        }catch (NotFoundHttpException $e){
            throw new NotFoundHttpException($e->getMessage());
        }

    }


    /**
     * [show 模板展示]
     *
     * @param $template
     * @param $variable
     * @author 11.
     */
    public function show($template, $variable)
    {
        return $this->container->make('response')->make($this->render($template, $variable));
    }
}