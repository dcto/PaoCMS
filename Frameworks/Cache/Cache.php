<?php

namespace PAO\Cache;

use PAO\Cache\Driver\ApcDriver;
use PAO\Cache\Driver\DriverInterface;
use PAO\Cache\Driver\FileDriver;
use PAO\Cache\Driver\RedisDriver;
use PAO\Cache\Driver\RetrievesMultipleKeys;
use PAO\Exception\SystemException;

class Cache
{
    use RetrievesMultipleKeys;

    /**
     * 驱动器
     * @var array
     */
    private $driver;


    public function __construct($driver = null)
    {
        if($driver){
            $this->setDefaultDriver($driver);
        }
    }

    /**
     * [apc APC缓存]
     *
     * @param string $prefix
     * @return ApcDriver
     */
    public function apc($prefix = 'pao_')
    {
        return new ApcDriver($prefix);
    }

    /**
     * [File 文件缓存]
     *
     * @param null $prefix
     * @return FileDriver
     * @author 11.
     */
    public function file($prefix = null)
    {
        return new FileDriver($prefix);
    }

    /**
     * [Redis 实例]
     *
     * @param string $server 连接的服务器名称
     * @return RedisDriver|\Redis
     * @author 11.
     */
    public function redis($name = 'default')
    {
        return new RedisDriver($name);
    }



    /**
     * Get a cache store instance by name.
     *
     * @param  string|null  $name
     * @return DriverInterface
     */
    public function driver($driver = null)
    {
        $driver = $driver ? $this->setDefaultDriver($driver) : $this->getDefaultDriver();

        if(!\Arr::get($this->driver, $driver)){
            if(!method_exists($this, $driver)){
                throw new SystemException('Invalid cache driver '.$driver.', check your cache config.');
            }
            $this->driver[$driver] = $this->$driver();
        }
        return $this->driver[$driver];
    }

    /**
     * Get the default cache driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return config('app.cache');
    }


    /**
     * Set the default cache driver name.
     *
     * @param  string  $name
     * @return bool
     */
    public function setDefaultDriver($driver)
    {
        if(!$driver){
            throw new SystemException('Invalid cache driver type.');
        }else if(!in_array($driver, get_class_methods($this))){
            throw new SystemException('Invalid '.$driver.' cache driver.');
        }
       return \Config::set('app.cache', $driver);
    }


    /**
     * [command 全局调用]
     *
     * @param       $method
     * @param array $parameters
     * @return $this->driver()
     * @author 11.
     */
    public function __call($method, array $parameters = [])
    {
        return call_user_func_array([$this->driver(), $method], $parameters);

    }
}