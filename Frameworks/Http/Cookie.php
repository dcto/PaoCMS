<?php

namespace PAO\Http;

use Illuminate\Container\Container;

class Cookie{

    /**
     * 容器
     * @var static
     */
    protected $container;



    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    /**
     * [set 设置cookie]
     *
     * @param            $name
     * @param null       $value
     * @param int        $expire
     * @param string     $path
     * @param null       $domain
     * @param bool|false $secure
     * @param bool|true  $httpOnly
     * @author 11.
     */
    public function set($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = true)
    {
        $cookie = new \Symfony\Component\HttpFoundation\Cookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

        $response = new \Symfony\Component\HttpFoundation\Response;

        $response->headers->setCookie($cookie);

        $response->sendHeaders();
    }


    /**
     * [has 判断cookie是否存在]
     *
     * @author 11.
     */
    public function has($name)
    {
        return ! is_null($this->get($name));
    }

    /**
     * [get 获取cookie]
     *
     * @param $name
     * @return mixed
     * @author 11.
     */
    public function get($name)
    {
        return $this->container->make('request')->cookies->get($name);
    }

    /**
     * [all 返回全部cookie]
     *
     * @return mixed
     * @author 11.
     */
    public function all()
    {
        return $this->container->make('request')->cookies->all();
    }


    /**
     * [del 删除cookie]
     *
     * @param $name
     * @author 11.
     */
    public function del($name)
    {
        $response = new \Symfony\Component\HttpFoundation\Response;
        $response->headers->clearCookie($name);
        $response->sendHeaders();
    }
}