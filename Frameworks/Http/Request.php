<?php

namespace PAO\Http;



use Illuminate\Container\Container;

class Request extends \Symfony\Component\HttpFoundation\Request
{

    public function url()
    {
        return $this->getUri();
    }


    /**
     * [input get方法别名]
     *
     * @param            $key
     * @param null       $default
     * @param bool|false $deep
     * @return mixed
     * @author 11.
     */
    public function input($key, $default = null, $deep = false)
    {
        return $this->get($key, $default, $deep);
    }


    public function cookie($key, $default)
    {
        return Container::getInstance()->make('cookie')->get($key);
    }

}


