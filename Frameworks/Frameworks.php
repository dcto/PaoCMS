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
use Illuminate\Events\EventServiceProvider;

/**
 * [Nexus 框架核心驱动集成类]
 *
 * Class Frameworks
 *
 * @package PAO
 * @version 20151123

 *
 */

version_compare(PHP_VERSION,'5.5.0','ge') || die('The php version least must 5.5.0 ');



class Frameworks extends Container
{

    /**
     * 已注入的模块
     * @var array
     */
    private $is_bindings = [];


    /**
     * 已注册的服务
     * @var array
     */
    private $is_providers = [];

    /**
     * 系统默认服务
     * @var array
     */
    private $systemBindings = [
        'config' => '_bindingsConfigure',
        'exception'=>'_bindingsException',
        'response'=>'_bindingsResponse',
        'request'=>'_bindingsRequest',
        'route'=>'_bindingsRoute',
        'view'=>'_bindingsView',
        'log'=>'_bindingsLogs',
        'db'=>'_bindingsDatabase',
        'session'=>'_bindingsSession',
        'cookie'=>'_bindingsCookie',
        'cache'=>'_bindingsCache'

    ];

    /**
     * 绑定外观模式别名
     * @var array
     */
    private $facadesAlias = [
        'Illuminate\Support\Facades\App' => 'PAO',
        'Illuminate\Support\Facades\Config' => 'Config',
        'Illuminate\Support\Facades\Request' => 'Request',
        'Illuminate\Support\Facades\Response' => 'Response',
        'Illuminate\Support\Facades\Event' => 'Event',
        'Illuminate\Support\Facades\DB' => 'DB',
        'Illuminate\Support\Facades\Cookie' => 'Cookie',
        'Illuminate\Support\Facades\Session' => 'Session',
        'Illuminate\Support\Facades\Cache' => 'Cache',
        'Illuminate\Support\Facades\Log' => 'Log'
    ];


    /**
     * [Issue 核心构造方法]
     * 主要完成一些初始化构件
     *
     */
    public function Issue()
    {
        /**
         * 设置系统时区
         */
        $timezone = $this->config('config.system.timezone');
        if ($timezone) {
            date_default_timezone_set($timezone);
        }

        /**
         * 注册静态核心
         */
        static::setInstance($this);

        /**
         * 注册动态核心
         */
        $this->instance('app', $this);

        /**
         * 注册核心容器别名
         */
        $this->registerContainerAliases();

        /**
         * 基本服务注册
         */
        $this->registerBaseServiceProviders();

        /**
         * 异常模块注入
         */
        $this->registerExceptionHandling();

        /**
         * 初始化外观模式
         */
        Facade::setFacadeApplication($this);

        /**
         * 注册外观模式别名
         */
        $this->registerFacadeAlias();

        /**
         * 启航
         */
        $this->Navigate();
    }



    /**
     * [make 全局注入方法]
     *
     * @param string $abstract      [方法名称]
     * @param array  $parameters    [方法参数]
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        if(isset($this->systemBindings[$abstract]) &&  !isset($this->is_bindings[$abstract]))
        {
            $this->{$this->systemBindings[$abstract]}();
            $this->is_bindings[$abstract] = true;
        }
        return parent::make($abstract, $parameters);
    }


    /**
     * [get make注入方法别名]
     *
     * @param       $abstract
     * @param array $parameters
     * @return mixed
     * @author 11.
     */
    public function get($abstract, array $parameters = [])
    {
        return $this->make($abstract, $parameters);
    }


    /**
     * [config 容器配置读取方法]
     *
     * @param $config [配置文件项]
     * @return mixed
     * @example $this->config('config.debug');
     */
    public function config($config)
    {
        return $this->make('config')->get($config);
    }



    /**
     * [注册核心容器中的别名]
     *
     * @return void
     */
    private function registerContainerAliases()
    {
        $this->aliases = [
            'Illuminate\Container\Container' => 'app',
            'Illuminate\Contracts\Routing\ResponseFactory' => 'PAO\Http\Response'
        ];
    }


    /**
     * [registerFacadeAlias 批量绑定外观模式别名]
     *
     * @author 11.
     */
    private function registerFacadeAlias()
    {
        foreach($this->facadesAlias as $facade => $alias)
        {

          //  class_alias($facade, $alias);
        }
    }

    /**
     * [Navigate 路由导航]
     *
     */
    private function Navigate()
    {
        $response = $this->make('route')->Dispatch();

        //重置Response响应
        if(!$response instanceof Response)
        {
            throw new SystemException('The Response Must be Instance of PAO\Response');
        }

        /**
         * 响应请求
         */
        $response->send();
    }


    /**
     * [register 服务注册器]
     *
     * @param $provider
     */
    private function register($provider, $options = [])
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
     * [registerExceptionHandling 异常服务注册]
     *
     */
    private function registerExceptionHandling()
    {
        //设置异常错误处理
        set_error_handler(function ($level, $message, $file = null, $line = 0) {
            if (error_reporting() & $level) {
                throw new \ErrorException($message, 0, $level, $file, $line);
            }
        });

        //设置抛出异常
        set_exception_handler(function ($e) {
            $this->make('exception')->Exception($e);
        });


    }

    /**
     * [registerBaseServiceProviders 其本服务注册]
     *
     * @author 11.
     */
    private function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));
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
     * [_bindingsResponse 注入响应方法]
     *
     * @author 11.
     */
    private function _bindingsResponse()
    {
        $this->singleton($this->getAlias('response'), function () {
            return new \PAO\Http\Response();
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

            /*
             * 工厂模式注入
            $connFactory = new \Illuminate\Database\Connectors\ConnectionFactory($this);
            $resolver = new \Illuminate\Database\ConnectionResolver();

            foreach($database as $db => $config)
            {
                $resolver->addConnection('default', $connFactory->make($config, $db););
            }

            $resolver->setDefaultConnection('default');
            \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
            return new DatabaseManager($this, $resolver);
            */
        });

    }

    /**
     * [_bindingsSession Session注入]
     *
     * @author 11.
     */
    private function _bindingsSession()
    {
        $this->singleton('session', function(){
            return new \PAO\Http\Session();
        });
    }

    private function _bindingsCookie()
    {
        $this->singleton('cookie', function(){
            return new \PAO\Http\Cookie($this);
        });
    }


    /**
     * [_bindingsCache 缓存系统绑定]
     *
     * @author 11.
     */
    private function _bindingsCache()
    {
        $this->singleton('cache', function(){
           return new \PAO\Cache\Cache($this);
        });
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
