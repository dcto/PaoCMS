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
    public function __construct()
    {
        $this->container = Container::getInstance();

    }



    /**
     * [File �ļ�����]
     *
     * @param null $cache
     * @return \PAO\Cache\FileSystem
     * @author 11.
     */
    public function File($cache = 'default')
    {
        if(isset($this->cache['file'][$cache]))
        {
            return $this->cache['file'][$cache];
        }

        return $this->cache['file'][$cache] =  new FileSystem($cache);
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