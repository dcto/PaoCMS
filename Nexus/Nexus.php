<?php

namespace Nexus;

use Illuminate\Container\Container;
use Nexus\Configure\Repository;
use Nexus\Exception\PAOException;
use Illuminate\Contracts\Routing\ResponseFactory;

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




    /**
     * 已加载配置文件
     * @var array
     */
    protected $is_config = [];

    /**
     * 已加载的服务
     * @var array
     */
    protected $is_bindings = [];


    /**
     * 系统默认服务
     * @var array
     */
    protected $systemBindings = [
        'config' => '_bindingsConfigure',
        'exception'=>'_bindingsException',


    ];


    public function __construct()
    {
        // 初始化本类
        static::setInstance($this);

        $this->instance('pao', $this);
    }


    public function wizard()
    {
        $this->_setExceptionHandling();


     echo '<hr />'.   $this->config('config.system.timezone');
    }


    public function DI($abstract, $parameters = [])
    {
        if(!isset($this->systemBindings[$abstract])) return false;
        if(!isset($this->is_bindings[$this->systemBindings[$abstract]])){
            $this->{$this->systemBindings[$abstract]}();
            $this->is_bindings[$this->systemBindings[$abstract]] = true;
        }
        return parent::make($abstract, $parameters);
    }



    public function todo($name){

    }


    public function config($config)
    {
        $config = trim($config,'.');
        $name = strstr($config,'.') ? strstr($config, '.', true) : $config;
        if(!isset($this->is_config[$name])){
            $this->_setConfigurations($name);
        }
        return $this->DI('config')->get($config);
    }


    private function _setConfigurations($name)
    {
        $PaoConfig = PAO.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.strtolower($name).'.php';
        $AppConfig = PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.strtolower($name).'.php';

        $Config = require($PaoConfig);
        if(is_readable($AppConfig)) {
            $Config = array_replace_recursive($Config, (array) require($AppConfig));
        }
        $this->DI('config')->set($name,  $Config);
        $this->is_config[$name] = true;
        return;
    }


    private function _setExceptionHandling()
    {
        //设置异常错误处理
        set_error_handler(function ($level, $message, $file = null, $line = 0) {
            if (error_reporting() & $level) {
                throw new \ErrorException($message, 0, $level, $file, $line);
            }
        });

        //设置抛出异常
      set_exception_handler(function ($e) {
            $this->DI('exception')->HandleError($e);
        });


    }


    private function _bindingsConfigure()
    {
        $this->singleton('config', function(){
            return new Repository();
        });
    }


    private function _bindingsException()
    {
        $this->singleton('exception', function(){
            return new PAOException($this);
        });
    }

}
