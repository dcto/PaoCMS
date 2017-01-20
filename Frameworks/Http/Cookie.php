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
    public function set($name, $value, $expire = null, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        $path   = is_null($path) ? config('cookie.path', '/') : $path;
        $expire = is_null($expire) ? config('cookie.expire', 0) : $expire;
        $domain = is_null($domain) ? config('cookie.domain', null) : $domain;
        $secure = is_null($secure) ? config('cookie.secure', false) : $secure;
        $httpOnly = is_null($httpOnly) ? config('cookie.httpOnly', true) : $httpOnly;

        make('response')->headers->setCookie(
            new HttpFoundation\Cookie($name, $value, time() + $expire, $path, $domain, $secure, $httpOnly)
        );

        make('response')->sendHeaders();
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
        make('response')->headers->clearCookie($name);
        make('response')->sendHeaders();
    }
}