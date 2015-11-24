<?php

namespace Nexus;

use Illuminate\Container\Container;

/**
 * @package Nexus
 * @version 20151123
 *
 */
class Nexus extends Container
{
    protected $config;

    protected $router;

    protected $request;


    public function __construct()
    {
        // 初始化本类
        static::setInstance($this);

        $this->instance('pao', $this);
        print_r($this->pao);
    }

	public function wizard()
	{

	}
}
