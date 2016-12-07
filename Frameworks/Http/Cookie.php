<?php

namespace PAO\Http;

use Symfony\Component\HttpFoundation;

class Cookie{

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
        $expire = $expire?:time()+$expire;

        $cookie = new HttpFoundation\Cookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

        $response = new HttpFoundation\Response();

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
        return make('request')->cookies->get($name);
    }

    /**
     * [all 返回全部cookie]
     *
     * @return mixed
     * @author 11.
     */
    public function all()
    {
        return make('request')->cookies->all();
    }


    /**
     * [del 删除cookie]
     *
     * @param $name
     * @author 11.
     */
    public function del($name)
    {
        $response = new HttpFoundation\Response;
        $response->headers->clearCookie($name);
        $response->sendHeaders();
    }
}