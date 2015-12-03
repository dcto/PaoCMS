<?php

namespace PAO;

use Illuminate\Container\Container;
use PAO\Http\Response;



class View
{
    /**
     * 容器
     * @var Container
     */
    protected $container;

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


    public function __construct(Container $container)
    {
        $this->container = $container;
        $view = $this->container->config('config.dir.view');
        $this->views = $view ? PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.$view : PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'View';
        $this->cache = $this->container->config('template.dir.cache');
        $this->debug =  $this->container->config('config.debug');


    }


    /**
     * [twig 模板引擎]
     *
     * @return \Twig_Environment
     * @author  11@pao11.com
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
            'debug' => true
        ));

        $twig->addGlobal('PAO', PAO);
        $twig->addGlobal('APP', APP);

        $url = new \Twig_SimpleFunction('U', array($this->container->DI('request'), 'getUri'));
        $twig->addFunction($url);

        return $twig;
    }

}