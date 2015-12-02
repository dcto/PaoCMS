<?php

namespace PAO;

use Illuminate\Contracts\Container\Container;



class View
{
    protected $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
        $view_dir = $this->container->config('config.dir.view');
        $view = $view_dir ? PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.$view_dir : PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'View';
        $loader = new \Twig_Loader_Filesystem($view);

        $twig  = new \Twig_Environment($loader, array(
            'cache' => $this->container->config('config.dir.cache'),
            'debug' => $this->container->config('config.debug'),
        ));

        $twig->addGlobal('PAO', PAO);
        $twig->addGlobal('APP', APP);

        $url = new \Twig_SimpleFunction('U', array($this->container->DI('request'), 'getUri'));
        $twig->addFunction($url);

        return $twig;
    }


    public function assign($var, $val = null)
    {
        if(is_array($var))
        {
            foreach($var as $key => $v)
            {
                $this->var[$key] = $v;
            }
        }else{
            $this->var = $val;
        }
    }
}