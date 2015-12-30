<?php

namespace PAO\Http;



use Symfony\Component\HttpFoundation\ParameterBag;

class Request extends \Symfony\Component\HttpFoundation\Request
{

    /**
     * �ع�Request����
     */
    public function __construct()
    {
        if ('cli-server' === php_sapi_name()) {
            if (array_key_exists('HTTP_CONTENT_LENGTH', $_SERVER)) {
                $_SERVER['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
            }
            if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
                $_SERVER['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
            }
        }
        parent::__construct($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);

        if (0 === strpos($this->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
            && in_array(strtoupper($this->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))
        ) {
            parse_str($this->getContent(), $data);
            $this->request = new ParameterBag($data);
        }
    }


    public function url()
    {
        return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
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

    /**
     * [method ��ȡ��ǰ����ʽ]
     *
     * @return string
     * @author 11.
     */
    public function method()
    {
        return $this->getMethod();
    }

    /**
     * [is �жϵ�ǰ·���Ƿ�ƥ��]
     *
     * @return bool
     * @author 11.
     */
    public function is()
    {
        foreach (func_get_args() as $pattern) {
            if (Str::is($pattern, urldecode($this->path()))) {
                return true;
            }
        }

        return false;
    }

    /**
     * [ip ��ȡ�ͻ���IP]
     *
     * @return string
     * @author 11.
     */
    public function ip()
    {
        return $this->getClientIp();
    }

    /**
     * [ips ��ȡ�ͻ�������IP]
     *
     * @return array
     * @author 11.
     */
    public function ips()
    {
        return $this->getClientIps();
    }


    /**
     * [path ��ȡ��ǰpathInfo]
     *
     * @return string
     * @author 11.
     */
    public function path()
    {
        $pattern = trim($this->getPathInfo(), '/');

        return $pattern == '' ? '/' : $pattern;
    }

    /**
     * [root ��ȡ��·��]
     *
     * @return string
     * @author 11.
     */
    public function root()
    {
        return rtrim($this->getSchemeAndHttpHost().$this->getBaseUrl(), '/');
    }
}


