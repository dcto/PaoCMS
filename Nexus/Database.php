<?php

namespace PAO;

use PAO\Exception\DBException;
use Illuminate\Container\Container;



class Database extends  \Illuminate\Database\Capsule\Manager
{

    public function __construct(Container $container)
    {
        parent::__construct($container);

        $database = $container->config('database');



        /**
         * 批量加数数据连接
         */
        foreach($database as $db => $config)
        {
            if(!is_array($config)) throw new DBException ('The Database Configures Was Error!');
            $this->addConnection($config, $db);
        }

        /**
         * 设置事件监听
         */
        $this->setEventDispatcher(new \Illuminate\Events\Dispatcher($container));

        /**
         * 设置默认数据库为default
         */
        $this->setupDefaultConfiguration();

        /**
         * 设置全局可用
         */
        $this->setAsGlobal();

        /**
         * 启动数据库
         */
        $this->bootEloquent();

    }


    /**
     * [__call 魔术方法实现Facades的呼叫]
     *
     * @param $method
     * @param $parameters
     * @return mixed
     * @author 11.
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->connection(), $method], $parameters);
    }


    /**
     * [debug 数据库调式]
     *
     * @author 11.
     */
    public function debug()
    {

    }


    /**
     * [getSql 返回查询语句]
     *
     * @author 11.
     */
    public function getSql()
    {
        $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
        $query = vsprintf($query, $bindings);

        Log::info($query, $data);
    }

}
