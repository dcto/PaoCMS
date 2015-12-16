<?php
namespace PAO\Cache;

use PAO\Cache\Redis;
use Illuminate\Container\Container;

class Cache
{

    /**
     * 容器
     * @var \Illuminate\Container\Container\
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
    public function __construct(Container $container)
    {
        $this->container = $container;

    }


    /**
     * [Redis 实例]
     *
     * @param string $server 连接的服务器名称
     * @return mixed
     * @author 11.
     */
    public function Redis($server = 'default')
    {
        if(isset($this->cache['redis'][$server]))
        {
            return $this->cache['redis'][$server];
        }
        $redis = new Redis($this->container);

        return $this->cache['redis'][$server] = $redis->connection($server);
    }



}