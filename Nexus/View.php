<?php

namespace PAO;

use Illuminate\Contracts\Container\Container;

class View
{
    protected $container;


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
                $this->var[$key] = $v;
            }
        }else{
            $this->var = $val;
        }
    }
}