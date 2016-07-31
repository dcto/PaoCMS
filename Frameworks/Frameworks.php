<?php

namespace PAO;

use PAO\Http\Response;
use PAO\Exception\PAOException;
use PAO\Support\Facades\Facade;
use PAO\Exception\SystemException;
use PAO\Services\SystemServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Pagination\PaginationServiceProvider;


/**
 * [Nexus 框架核心驱动集成类]
 *
 * Class Frameworks
 *
 * @package PAO
 * @version 20151123
 *
 */
defined('APP') || die('You must define APP name in your current script.');

version_compare(PHP_VERSION,'5.5.0','ge') || die('The php version least must 5.5.0 ');


/**
 * 核心框架
 * Class Frameworks
 * @package PAO
 */
class Frameworks extends Container
{

    const VERSION = 'v1.2';

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
         * 注册核心容器
         */
        $this->registerContainerAliases();

        /**
         * 配置系统环境
         */
        $this->registerSystemEnvironment();

        /**
         * 异常模块注入
         */
        $this->registerExceptionHandling();

        /**
         * 初始化外观模式
         */
        Facade::setFacadeApplication($this);

        /**
         * 基本服务注册
         */
        $this->registerBaseServiceProviders();

        /**
         * 启航
         */
        $this->Navigate();
    }


    /**
     * [make 全局注入方法]
     *
     * @param string $abstract [方法名称]
     * @param array $parameters [方法参数]
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        if (!isset($this->is_bindings[$abstract]) && $this->isAlias($abstract)) {
            $this->singleton($abstract, $this->getAlias($abstract));
            $this->is_bindings[$abstract] = true;
        }

        return parent::make($abstract, $parameters);
    }

    /**
     * [service 应用服务注册器]
     *
     * @param $abstract
     * @param array $parameters
     * @return mixed
     */
    public function service($abstract, array $parameters = [])
    {
        $abstract = 'App\\Service\\'.$abstract;

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
        $response = $this->make('router')->dispatch();

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
     * [register 服务提供者注册器]
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
     * [注册核心容器中的别名]
     *
     * @return void
     */
    private function registerContainerAliases()
    {
        $this->aliases = [
            'route' => 'PAO\Route',
            'router' => 'PAO\Routing\Router',
            'config' => 'PAO\Configure\Repository',
            'request' => 'PAO\Http\Request',
            'response' => 'PAO\Http\Response',
            'cookie' => 'PAO\Http\Cookie',
            'session' => 'PAO\Http\Session',
            'crypt' => 'PAO\Crypt\Crypt',
            'captcha' => 'PAO\Captcha\Captcha',
            'validator' => 'PAO\Validator',
            'lang' => 'PAO\Translator',
            'view' => 'PAO\View',
            'file' => 'PAO\FileSystem\Files',
            'cache' => 'PAO\Cache\Cache',
            'log' => 'PAO\Logger',
            'db' => 'PAO\Database'
        ];

        foreach($this->aliases as $alias=>$value)
        {
            $alias = ucfirst($alias);
            class_alias(__NAMESPACE__.'\\Support\\Facades\\'.$alias, $alias);
        }
    }


    /**
     * [registerExceptionHandling 异常服务注册]
     *
     */
    private function registerExceptionHandling()
    {
        $PAOException = new PAOException();

        //设置异常错误处理
        set_error_handler(function ($level, $message, $file = null, $line = 0) {
            if (error_reporting() & $level) {
                throw new \ErrorException($message, 0, $level, $file, $line);
            }
        });

        //设置抛出异常
        set_exception_handler(function ($e)use($PAOException) {
            $PAOException->Exception($e);
        });

        //致命错误处理
        register_shutdown_function(function()use($PAOException){

            $e = error_get_last();

            if(in_array($e['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]))
            {
                $e['function'] = $e['type'];
                $error[] = $e;
                die($PAOException->display($e['type'], $e['message'], $error));
            }
        });
    }

    /**
     * [registerBaseServiceProviders 基本服务注册]
     *
     * @author 11.
     */
    private function registerBaseServiceProviders()
    {
        /**
         * 系统服务
         */
        $this->register(new SystemServiceProvider($this));

        /**
         * 事件服务
         */
        $this->register(new EventServiceProvider($this));

        /**
         * 分页服务
         */
        $this->register(new PaginationServiceProvider($this));
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
        if($this->config('config.debug')) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }else{
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }

        /**
         * 设置系统时区
         */
        if ($timezone = $this->config('config.timezone')) {
            date_default_timezone_set($timezone);
        }

        /**
         * 设置环境编码
         * @var [type]
         */
        if ($charset = $this->config('config.charset')) {
            mb_internal_encoding($charset);
        }

    }
}
