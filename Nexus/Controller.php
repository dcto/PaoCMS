<?php

namespace PAO;

use Illuminate\Container\Container;


class Controller
{
    protected $container;


    public function __construct(Container $container)
    {
        $this->container = $container;

    }
}