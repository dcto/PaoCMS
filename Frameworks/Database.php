<?php

namespace PAO;


use PAO\Exception\DBException;
use Illuminate\Container\Container;




class Database extends  \Illuminate\Database\Capsule\Manager
{

    public function __construct()
    {
        /**
         * 设置容器
         */
        $this->setupContainer(Container::getInstance());

        /**
         * 创建数据库实例
         */
        $this->setupManager();

        /**
         * 设置配置
         */
        $database = $this->getContainer()->make('config')->get('database');

        /**
         * 批量加数数据连接
         */
        foreach($database as $db => $config)
        {
            if(!is_array($config)) throw new DBException ('The database configures was error!');
            $this->addConnection($config, $db);
        }

        /**
         * 注册数据库监听
         */
        $this->setEventDispatcher(new \Illuminate\Events\Dispatcher($this->getContainer()));

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

        /**
         * 判断是否打开调式sql模式
         */
        if($this->getContainer()->config('config.debug')) {
            $this->connection()->enableQueryLog();
        }

        /**
         * 注入分页服务
         */
        //$container->register('Illuminate\Pagination\PaginationServiceProvider');

        /*
        Paginator::currentPathResolver(function () {
            return $this->make('request')->url();
        });

        Paginator::currentPageResolver(function ($pageName = 'page') {
            return $this->make('request')->get($pageName);
        });
        */
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
     * [getSql 返回查询语句]
     *
     * @author 11.
     */
    public function getSql()
    {
        foreach($this->getConnection()->getQueryLog() as $sql)
        {
            $query = str_replace(array('%', '?'), array('%%', '%s'), $sql['query']);
            $query = vsprintf($query, $sql['bindings']);
            echo '<pre style="font-weight:bold;">SQL: <code style=" color:#FF0000">'.($query).' </code><i style="color:#ddd"> '.$sql['time'].'</i></pre>';
        }

        /*监听方式
        Event::listen('illuminate.query', function($query, $bindings, $time, $name)
        {

            // Format binding data for sql insertion
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else if (is_string($binding)) {
                    $bindings[$i] = "'$binding'";
                }
            }

            // Insert bindings into query
            $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
            $query = vsprintf($query, $bindings);

            // Debug SQL queries
            echo $name.  '->SQL: [ ' . $query . ' ]'. $time .'<br />';
        });
        */
    }

}
