<?php

namespace PAO\Http;



use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
     * [url ��ȡ��ǰURL]
     *
     * @return string
     * @author 11.
     */
    public function url()
    {
        return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    /**
     * [all ��������]
     *
     * @return array
     * @author 11.
     */
    public function all()
    {
        return array_replace_recursive($this->input(), $this->files->all());
    }


    /**
     * [has �Ƿ����]
     *
     * @param $key
     * @return bool
     * @author 11.
     */
    public function has($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        $input = $this->all();

        foreach ($keys as $value) {
            if (! array_key_exists($value, $input)) {
                return false;
            }
        }

        return true;
    }

    /**
     * [get get��������]
     *
     * @param            $key
     * @param null       $default
     * @return mixed
     * @author 11.
     */
    public function get($key = null, $default = null)
    {
        $input = $this->getInputSource()->all() + $this->query->all();

        return Arr::get($input, $key, $default);
    }

    /**
     * [input get��������]
     *
     * @param            $key
     * @param null       $default
     * @return mixed
     * @author 11.
     */
    public function input($key = null, $default = null)
    {
        return $this->get($key, $default);
    }

    /**
     * [header]
     *
     * @param null $key
     * @param null $default
     * @return mixed
     * @author 11.
     */
    public function header($key = null, $default = null)
    {
        return $this->retrieve('headers', $key, $default);
    }

    /**
     * [server ��ȡserver]
     *
     * @param null $key
     * @param null $default
     * @return mixed
     * @author 11.
     */
    public function server($key = null, $default =null)
    {
        return $this->retrieve('server', $key, $default);
    }

    /**
     * [file ��ȡ�ϴ��ļ�]
     *
     * @param null $key
     * @param null $default
     * @return mixed
     * @author 11.
     */
    public function file($key = null, $default = null)
    {
        return Arr::get($this->files->all(), $key, $default);
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
        if ($key) {
            return $this->cookies->get($key);
        } else {
            return $this->cookies->all();
        }
    }

    /**
     * [isJson �ж��Ƿ�Ϊjson]
     *
     * @return bool
     * @author 11.
     */
    public function isJson()
    {
        return Str::contains($this->header('CONTENT_TYPE'), '/json');
    }

    /**
     * [json ��ȡJSON����]
     *
     * @param null $key
     * @param null $default
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @author 11.
     */
    public function json($key = null, $default = null)
    {
        if (!isset($this->json)) {
            $this->json = new ParameterBag((array)json_decode($this->getContent(), true));
        }

        if (is_null($key)) {
            return $this->json;
        }

        return Arr::get($this->json->all(), $key, $default);
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
        $pattern = $this->getPathInfo();

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
        return rtrim($this->getSchemeAndHttpHost() . $this->getBasePath(), '/');
    }

    public function baseurl()
    {
        return rtrim($this->getSchemeAndHttpHost() . $this->getBaseUrl(), '/');
    }

    /**
     * [segment ����������ȡpath]
     *
     * @param      $index [��1��ʼ]
     * @param null $default
     * @return mixed
     * @author 11.
     */
    public function segment($index, $default = null)
    {
        return Arr::get($this->segments(), $index - 1, $default);
    }

    /**
     * [segments �ֽ�PATH]
     *
     * @return array
     * @author 11.
     */
    public function segments()
    {
        $segments = explode('/', $this->path());
        return array_values(array_filter($segments, function ($v) { return $v != ''; }));
    }



    /**
     * [secure �ж��Ƿ��ǰ�ȫ����]
     *
     * @return bool
     * @author 11.
     */
    public function secure()
    {
        return $this->isSecure();
    }


    /**
     * [retrieve]
     *
     * @param $source
     * @param $key
     * @param $default
     * @return mixed
     * @author 11.
     */
    protected function retrieve($source, $key, $default)
    {
        if (is_null($key)) {
            return $this->$source->all();
        }

        return $this->$source->get($key, $default, true);
    }


    /**
     * [getInputSource ��ȡ���󷽷�]
     *
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @author 11.
     */
    protected function getInputSource()
    {
        if ($this->isJson()) {
            return $this->json();
        }

        return $this->method() == 'GET' ? $this->query : $this->request;
    }
}


