<?php

namespace PAO\Cache;

use PAO\Cache\Driver\ApcDriver;
use PAO\Cache\Driver\FileDriver;
use PAO\Cache\Driver\FileSystemDriver;
use PAO\Cache\Driver\RedisDriver;
use PAO\Exception\SystemException;

class Cache
{

    /**
     * 驱动器
     * @var array
     */
    private $driver;

    /**
     * 缓存项目
     * @var
     */
    private $cache;


    public function __construct($driver = null)
    {
        $this->setDefaultDriver($driver);
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
     * @return mixed
     */
    public function driver($driver = null)
    {
        if($driver && !in_array($driver, get_class_methods($this))){
            throw new SystemException('Unknown the ['.$driver.'] cache driver.');
        }

        $driver = $driver ?: $this->getDefaultDriver();

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
     * @return void
     */
    public function setDefaultDriver($name)
    {
        app('config')->set('app.cache', $name);
    }
}