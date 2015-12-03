<?php

namespace PAO;

use Illuminate\Container\Container;


class Controller
{
    public $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}