<?php

namespace PAO;



use PAO\Http\Request;
use PAO\Http\Response;
use PAO\Configure\Repository;
use PAO\Exception\PAOException;
use Illuminate\Container\Container;
use PAO\Exception\SystemException;
use PAO\Exception\ServiceException;
use PAO\Exception\NotFoundHttpException;


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
        'request'=>'_bindingsRequest',
        'route'=>'_bindingsRoute',
        'view'=>'_bindingsView',


    ];


    public function __construct()
    {
        //注入核心类
        static::setInstance($this);

        $this->instance('pao', $this);

        ;
        $this->registerContainerAliases();

    }

    /**
     * 注册核心容器中的别名
     *
     * @return void
     */
    protected function registerContainerAliases()
    {
        $this->aliases = [

            'Illuminate\Container\Container' => 'pao',
        ];
    }

    public function Issue()
    {
        //注入异常模块
        $this->_setExceptionHandling();

        $this->_setExceptionHandling();
        $this->DI('route')->get(['/gg'=>['as'=>'index', 'to'=>'index@ddd']]);
        //起航
        $this->Navigate();


        //$timezone = $this->config('config.system.timezone');
    }


    public function DI($abstract, $parameters = [])
    {
        if(isset($this->systemBindings[$abstract]) &&  !isset($this->is_bindings[$this->systemBindings[$abstract]])  ){
            $this->{$this->systemBindings[$abstract]}();
            $this->is_bindings[$this->systemBindings[$abstract]] = true;
        }
        return parent::make($abstract, $parameters);
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


    public function Navigate()
    {
        $response = $this->DI('route')->dispatch();

        //重置Response响应
        if(!$response instanceof Response)
        {
           // $response = new Response($response);
            throw new SystemException('The Response Must be Instance of PAO\Response');
        }

        $response->send();
    }



    private function _setConfigurations($name)
    {
        $PaoConfig = PAO.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.strtolower($name).'.php';
        $AppConfig = PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.strtolower($name).'.php';
        if(!is_readable($PaoConfig)) throw new SystemException('The config file is not available in The '. $PaoConfig);
        $Config = (array) require($PaoConfig);

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
            //print_r($e);
            $this->DI('exception')->Exception($e);
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


    private function _bindingsRequest()
    {
        $this->singleton('request', function(){
            $this->request = Request::createFromGlobals();
            return $this->request;
        });
    }

    private function _bindingsRoute()
    {
        $this->singleton('route', function(){
            return new \PAO\Route($this);
        });
    }

    private function _bindingsView()
    {
        $this->singleton('view', function(){
            return new \PAO\View($this);
        });
    }
}
