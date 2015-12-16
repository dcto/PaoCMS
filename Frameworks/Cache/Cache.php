<?php
namespace PAO\Cache;

use PAO\Cache\Redis;
use Illuminate\Container\Container;

class Cache
{

    /**
     * ����
     * @var \Illuminate\Container\Container\
     */
    protected $container;


    /**
     * ������Ŀ
     * @var array
     */
    protected $cache = [];

    /**
     * ��ʼ������
     * @param \Illuminate\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

    }


    /**
     * [Redis ʵ��]
     *
     * @param string $server ���ӵķ���������
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