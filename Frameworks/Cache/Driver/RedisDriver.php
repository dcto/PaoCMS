<?php

namespace PAO\Cache\Driver;


use PAO\Exception\SystemException;

/**
 * Class RedisDriver
 * @package PAO\Cache\Driver
 * @see \Redis
 */
class RedisDriver implements DriverInterface
{

    /**
     * redis servers config
     *
     * @var array|\Redis
     */
    private $servers = array();



    public function __construct($server = 'default')
    {
        $this->server($server);
    }


    /**
     * @param string $server
     * @return mixed|\Redis
     */
    public function server($server = 'default')
    {

        if(!isset($this->servers[$server])){

            $config = config('cache.redis.'. $server);

            if(!$config){
                throw new SystemException('Unable load ['.$server.'] redis server configure.');
            }

            $this->servers[$server] = new \Redis();
            if(\Arr::get($config, 'persistent', 0)){
                $this->servers[$server]->pconnect($config['host'], $config['port'], $config['timeout']);
            }else {
                $this->servers[$server]->connect($config['host'], $config['port'], $config['timeout']);
            }

            if(isset($config['password']) && !empty($config['password']))
            {
                $this->servers[$server]->auth(trim($config['password']));
            }

            $this->servers[$server]->select($config['database']?:0);

            foreach($config['options'] as $key => $val)
            {
                $this->servers[$server]->setOption($key, $val);
            }
        }

        return $this->servers[$server];
    }

    /**
     * Check an item from the cache by key.
     *
     * @param  string|array $key
     * @return mixed
     */
    public function has($key)
    {
        return $this->server()->exists($key);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get($key)
    {
        if (! is_null($value = $this->server()->get($key))) {
            return is_numeric($value) ? $value : unserialize($value);
        }
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string  $key 缓存键
     * @param  mixed   $value 缓存值
     * @param  int     $time 缓存时间
     * @return bool
     */
    public function set($key, $value, $time = 86400)
    {
        $value = is_numeric($value) ? $value : serialize($value);
        return $this->server()->setex($key, $time, $value);
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param  array  $keys
     * @return array
     */
    public function gets(array $keys)
    {
        $return = array();

        $values = $this->server()->mget($keys);

        foreach ($values as $index => $value) {
            $return[$keys[$index]] = is_numeric($value) ? $value : unserialize($value);
        }
        return $return;
    }

    /**
     * Store multiple items in the cache for a given number of minutes.
     *
     * @param  array  $values
     * @param  int  $minutes
     * @return
     */
    public function sets(array $values, $time = 86400)
    {
        $this->server()->multi();

        foreach ($values as $key => $value) {
            $this->set($key, $value, $time);
        }

       return $this->server()->exec();
    }


    /**
     * Increment the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return int
     */
    public function increment($key, $value = 1)
    {
        return $this->server()->incrBy($key, $value);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return int
     */
    public function decrement($key, $value = 1)
    {
        return $this->server()->decrBy($key, $value);
    }

    /**
     * 持久化保存
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function save($key, $value)
    {
        $value = is_numeric($value) ? $value : serialize($value);

        $this->server()->set($key, $value);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function del($key)
    {
        return (bool) $this->server()->del($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush()
    {
        return $this->server()->flushDB();
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function prefix($prefix = false)
    {
        return $this->server()->_prefix($prefix);
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
        return call_user_func_array([$this->server(), $method], $parameters);

    }


    /**
     * [__call 魔术调用redis方法]
     *
     * @param $method
     * @param $parameters
     * @return static
     * @author 11.
     */
    public function __call($method, $parameters)
    {
        return $this->command($method, $parameters);
    }
}