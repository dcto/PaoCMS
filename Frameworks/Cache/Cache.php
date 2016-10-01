<?php

namespace PAO\Cache;

use Illuminate\Container\Container;

class Cache
{

    /**
     * 容器
     * @var \Illuminate\Container\Container
     */
    protected $container;


    /**
     * 缓存项目
     * @var array
     */
    protected $cache = [];

    /**
     * 初始化缓存
     * @param \Illuminate\Container\Container $container
     */
    public function __construct()
    {
        $this->container = Container::getInstance();

    }



    /**
     * [File 文件缓存]
     *
     * @param null $cache
     * @return \PAO\Cache\FileSystem
     * @author 11.
     */
    public function file($cache = 'default')
    {
        if(isset($this->cache['file'][$cache]))
        {
            return $this->cache['file'][$cache];
        }

        return $this->cache['file'][$cache] =  new FileSystem($cache);
    }


    /**
     * [Redis 实例]
     *
     * @param string $server 连接的服务器名称
     * @return \Redis
     * @author 11.
     */
    public function redis($server = 'default')
    {
        if(isset($this->cache['redis'][$server]))
        {
            return $this->cache['redis'][$server];
        }
        $redis = new Redis($this->container);

        return $this->cache['redis'][$server] = $redis->connection($server);
    }



}