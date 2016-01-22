<?php

namespace PAO;



use PAO\Http\Response;
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


/**
 * 核心框架
 * Class Frameworks
 * @package PAO
 */
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
     * [Issue 核心应用构造方法]
     */
    public function Issue()
    {
        /**
         * 核心框架注入
         */
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance('Illuminate\Container\Container', $this);

        /**
         * 异常模块注入
         */
        $this->registerExceptionHandling();

        /**
         * 注册核心容器别名
         */
        $this->registerContainerAliases();

        /**
         * 基本服务注册
         */
        $this->registerBaseServiceProviders();

        /**
         * 初始化配置系统环境
         */
        $this->registerSystemEnvironment();

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
     * [注册核心容器中的别名]
     *
     * @return void
     */
    private function registerContainerAliases()
    {
        $this->aliases = [
            'route'=>'PAO\Route',
            'request'=>'PAO\Http\Request',
            'response'=>'PAO\Http\Response',
            'cookie'=>'PAO\Http\Cookie',
            'session'=>'PAO\Http\Session',
            'captcha'=>'PAO\Captcha\Captcha',
            'config'=>'PAO\Configure\Repository',
            'exception'=>'PAO\Exception\PAOException',
            'db'=>'PAO\Database',
            'view'=>'PAO\View',
            'cache'=>'PAO\Cache\Cache',
            'log'=>'PAO\Logger',
            'translator'=>'PAO\Translator',
            'Illuminate\Contracts\Routing\ResponseFactory' => 'PAO\Http\Response'
        ];

        $aliases = (array) $this->config('service');
        if($alias = array_intersect_key($this->aliases, $aliases))
        {
            $duplicates = implode(',', array_keys($aliases));
            throw new SystemException("The Service Alias [$duplicates] Was Duplicate!");
        }
        $this->aliases = array_merge($this->aliases, $aliases);
    }

    /**
     * [registerFacadeAlias 批量绑定外观模式别名]
     *
     * @author 11.
     */
    private function registerFacadeAlias()
    {
        $facadesAlias = [
            'PAO' => 'Illuminate\Support\Facades\App',
            'Config' => 'Illuminate\Support\Facades\Config',
            'Request' => 'Illuminate\Support\Facades\Request',
            'Response' => 'Illuminate\Support\Facades\Response',
            'Event' => 'Illuminate\Support\Facades\Event',
            'DB' => 'Illuminate\Support\Facades\DB',
            'Cookie' => 'Illuminate\Support\Facades\Cookie',
            'Session' => 'Illuminate\Support\Facades\Session',
            'Cache' => 'Illuminate\Support\Facades\Cache',
            'Log' => 'Illuminate\Support\Facades\Log'
        ];
        foreach($facadesAlias as $alias => $facade)
        {
            class_alias($facade, $alias);
        }
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
        if(!isset($this->is_bindings[$abstract]) && $this->isAlias($abstract) )
        {
            $this->singleton($abstract, $this->getAlias($abstract));
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
     * [Navigate 路由导航]
     *
     */
    private function Navigate()
    {
        $response = $this->make('route')->Dispatch();

        //重置Response
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

        //致命错误处理
        register_shutdown_function(function(){

            $e = error_get_last();

            if(in_array($e['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]))
            {
                $e['function'] = $e['type'];
                $error[] = $e;
                die($this->make('exception')->display($e['type'], $e['message'], $error));
            }
        });
    }

    /**
     * [registerBaseServiceProviders 事件服务注册]
     *
     * @author 11.
     */
    private function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));
    }


    /**
     * [registerSystemEnvironment 初始化配置系统环境]
     *
     * @author 11.
     */
    private function registerSystemEnvironment()
    {

        /**
         * 设置错误报告
         */
        if(!$this->config('config.debug'))
        {
            error_reporting(0);
        }

        /**
         * 设置系统时区
         */
        $timezone = $this->config('config.timezone');
        if ($timezone) {
            date_default_timezone_set($timezone);
        }

        /**
         * 设置字符编码
         * @var [type]
         */
        $charset = $this->config('config.charset');
        if ($charset) {
            mb_internal_encoding($charset);
        }

    }
}
