<?php

namespace PAO\Http;



class Request extends \Symfony\Component\HttpFoundation\Request
{

    public function url()
    {
        return $this->getUri();
    }


    /**
     * [input get��������]
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


    /**
     * [cookie �ع�cookie������ӦFacades����]
     *
     * @param $key
     * @param $default
     * @return mixed
     * @author 11.
     */
    public function cookie($key = null)
    {
        if($key) {
            return $this->cookies->get($key);
        }else{
            return $this->cookies->all();
        }
    }

}


