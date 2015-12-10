<?php

namespace PAO;


use PAO\Http\Request;
use PAO\Http\Response;
use PAO\Configure\Repository;
use PAO\Exception\PAOException;
use PAO\Exception\SystemException;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;


/**
 * [Nexus 框架核心驱动集成类]
 *
 * Class Nexus
 *
 * @package PAO
 * @version 20151123

 *
 */

version_compare(PHP_VERSION,'5.3.0','ge') || die('The php version least must 5.3.0 ');



class Nexus extends Container
{

    /**
     * 配置文件预读
     * @var array
     */
    protected $config = [];


    /**
     * 已加载配置文件
     * @var array
     */
    protected $is_config = [];

    /**
     * 已注入的模块
     * @var array
     */
    protected $is_bindings = [];


    /**
     * 已注册的服务
     * @var array
     */
    protected $is_providers = [];

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
        'log'=>'_bindingsLogs',
        'db'=>'_bindingsDatabase'

    ];



    public function __construct()
    {
        $timezone = $this->config('config.system.timezone');

        if ($timezone) {
            date_default_timezone_set($timezone);
        }


        //注入核心类
        static::setInstance($this);

        $this->instance('app', $this);

        $this->registerContainerAliases();

        Facade::setFacadeApplication($this);
    }

    /**
     * [注册核心容器中的别名]
     *
     * @return void
     */
    protected function registerContainerAliases()
    {
        $this->aliases = [

            'Illuminate\Container\Container' => 'app',
            'Illuminate\Database\DatabaseManager' => 'db',

        ];
    }

    /**
     * [Issue 核心构造方法]
     * 主要完成一些初始化构件
     *
     */
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

    /**
     * [DI 全局注入方法]
     *
     * @param string $abstract      [方法名称]
     * @param array  $parameters    [方法参数]
     * @return mixed
     */
    public function DI($abstract, $parameters = [])
    {
        if(isset($this->systemBindings[$abstract]) &&  !isset($this->is_bindings[$this->systemBindings[$abstract]])  )
        {
            $this->{$this->systemBindings[$abstract]}();
            $this->is_bindings[$this->systemBindings[$abstract]] = true;
        }
        return parent::make($abstract, $parameters);
    }

    /**
     * [config 全局配置方法]
     *
     * @param $config [配置文件项]
     * @return mixed
     * @example $this->config('config.debug');
     */
    public function config($config)
    {
        $config = trim($config,'.');
        $name = strstr($config,'.') ? strstr($config, '.', true) : $config;
        if(!isset($this->is_config[$name])){
            $this->_setConfigurations($name);
        }
        return $this->DI('config')->get($config);
    }


    /**
     * [Navigate 路由导航]
     *
     */
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


    /**
     * [register 服务注册器]
     *
     * @param $provider
     */
    public function register($provider, $options = [])
    {
        if(!$provider instanceof ServiceProvider)
        {
            $provider = new $provider($this);
        }

        if(isset($this->is_providers[$providerName = get_class($provider)]))
        {
            return;
        }

        $this->is_providers[$providerName] = true;
        $provider->register();
        $provider->boot();
    }


    /**
     * [_setConfigurations 配置服务注册]
     *
     * @param $name
     */
    private function _setConfigurations($name)
    {
        $PaoConfig = PAO.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.strtolower($name).'.php';
        $AppConfig = PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.strtolower($name).'.php';
        if(!is_readable($PaoConfig)) throw new SystemException('The config file is not available in The '. $PaoConfig);
        $Config = (array) require($PaoConfig);

        if(is_readable($AppConfig))
        {
            $Config = array_replace_recursive($Config, (array) require($AppConfig));
        }
        $this->DI('config')->set($name,  $Config);
        $this->is_config[$name] = true;
        return;
    }


    /**
     * [_setExceptionHandling 异常服务注册]
     *
     */
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


    /**
     * [_bindingsConfigure 配置服务绑定]
     *
     */
    private function _bindingsConfigure()
    {
        $this->singleton('config', function(){
            return new Repository();
        });
    }


    /**
     * [_bindingsException 异常服务绑定]
     *

     */
    private function _bindingsException()
    {
        $this->singleton('exception', function(){
            return new PAOException($this);
        });
    }


    /**
     * [_bindingsRequest Request服务绑定]
     *
     */
    private function _bindingsRequest()
    {
        $this->singleton('request', function(){
            return Request::createFromGlobals();
        });
    }

    /**
     * [_bindingsRoute 路由组件绑定]
     *
     */
    private function _bindingsRoute()
    {
        $this->singleton('route', function(){
            return new \PAO\Route($this);
        });
    }

    /**
     * [_bindingsView 视图模块绑定]
     *
     */
    private function _bindingsView()
    {
        $this->singleton('view', function(){
            return new \PAO\View($this);
        });
    }

    /**
     * [__bindingsDatabase 数据库服务]
     */
    private function _bindingsDatabase()

    {
        $this->singleton('db', function(){


            return new \PAO\Database($this);

/*            $connFactory = new \Illuminate\Database\Connectors\ConnectionFactory($this);
            $resolver = new \Illuminate\Database\ConnectionResolver();

            foreach($database as $db => $config)
            {
                $conn = $connFactory->make($config, $db);

                $resolver->addConnection('default', $conn);
            }


            $resolver->setDefaultConnection('default');

            \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
            return new DatabaseManager($this, $resolver);*/



        });





//        class_alias('Illuminate\Database\Capsule\Manager', 'DB');
        //class_alias('Illuminate\Support\Facades\DB', 'DB');
//


        //$this->register('Illuminate\Database\DatabaseServiceProvider');
        //$this->register('Illuminate\Pagination\PaginationServiceProvider');

    }

    /**
     * [_bindingsLogs 日志系统绑定]
     *
     */
    private function _bindingsLogs()
    {
        $this->singleton('log', function(){
            return new \PAO\Logger($this);
        });
    }
}
