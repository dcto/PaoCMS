<?php

namespace PAO\Http;

use Symfony\Component\HttpFoundation;

class Cookie{

    /**
     * The default path (if specified).
     *
     * @var string
     */
    protected $path = '/';

    /**
     * The default domain (if specified).
     *
     * @var string
     */
    protected $domain = null;

    /**
     * The default secure setting (defaults to false).
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * http only
     *
     * @var bool
     */
    protected $http_only = true;

    /**
     * encrypt cookie value
     *
     * @var bool
     */
    protected $encrypt = false;


    /**
     * Cookie constructor.
     */
    public function __construct()
    {
        $this->path = config('cookie.path', '/');
        $this->domain = config('cookie.domain', null);
        $this->secure = config('cookie.secure', null);
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
        $expire = $expire?time()+$expire:$expire;

        make('response')->headers->setCookie(
            new HttpFoundation\Cookie($name, $value, $expire, $path, $domain, $secure, $httpOnly)
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