<?php

namespace PAO\Cache;


use PAO\Exception\SystemException;
use Illuminate\Container\Container;


class Redis
{
    /**
     * 配置实例
     * @var
     */
    protected $clients;


    /**
     * 初始化redis连接
     * @param \Illuminate\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $configs = $container->config('cache.redis');

        try{

            foreach($configs as $server => $config)
            {
                $redis = new \Redis();
                if(isset($config['persistent']) && $config['persistent']){
                    $redis->pconnect($config['host'], $config['port'], $config['timeout']);
                }else {
                    $redis->connect($config['host'], $config['port'], $config['timeout']);
                }

                if(isset($config['password']) && !empty($config['password']))
                {
                    echo 'password';
                    $redis->auth(trim($config['password']));
                }

                $redis->select($config['database']?:0);

                foreach( $config['options'] as $key => $val)
                {
                    $redis->setOption($key, $val);
                }

                $this->clients[$server] = $redis;
            }

        }catch (\Exception $e)
        {
                throw new SystemException('Connect redis error ['. $e->getMessage().']');
        }

    }


    /**
     * [connection 连接服务器]
     *
     * @param string $name
     * @return mixed
     * @author 11.
     */
    public function connection( $name = 'default')
    {
        if(!isset($this->clients[$name])){
            throw new SystemException('The "'.$name.'" redis server was not found');
        }

        if(!($this->clients[$name] instanceof \Redis)){
            throw new SystemException('The "'. $name. '" must instanceof \Redis Object');
        }

        return $this->clients[$name];
    }

    /**
     * [command 命令行运行]
     *
     * @param       $method
     * @param array $parameters
     * @author 11.
     */
    public function command($method, array $parameters = [])
    {
        return call_user_func_array([$this->connection(), $method], $parameters);
    }


    /**
     * [__call 魔术调用redis方法]
     *
     * @param $method 调用方法
     * @param $parameters 调用参数
     * @author 11.
     */
    public function __call($method, $parameters)
    {
        return $this->command($method, $parameters);
    }

}
