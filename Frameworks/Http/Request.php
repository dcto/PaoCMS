<?php

namespace PAO\Http;



use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\ParameterBag;

class Request extends \Symfony\Component\HttpFoundation\Request
{

    /**
     * 重构Request方法
     */
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
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
     * [is 判断当前路径是否匹配]
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
     * [url 获取当前URL]
     *
     * @return string
     * @author 11.
     */
    public function url()
    {
        return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    /**
     * [uri 获取当前url包含所有参数]
     * @param null $cast [排除或抽取批定URL参数]
     */
    public function uri($cast = null)
    {
        $queryString = $this->all();
        if($cast){
            if(substr($cast, 0, 1) == '!') {
                unset($queryString[ltrim($cast, '!')]);
            }else{
                $queryString = array_key_exists($cast, $queryString) ? array($cast=>$queryString[$cast]) : null;
            }
        }
        $uri = $this->all() ? '?'. http_build_query($queryString) : '';
        return $uri = $this->url().$uri;
    }

    /**
     * [all 返回所有]
     *
     * @return array
     * @author 11.
     */
    public function all()
    {
        return array_replace_recursive($this->input(), $this->files->all());
    }


    /**
     * [has 是否存在]
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
     * [get get方法别名]
     *
     * @param            $key
     * @param null       $default
     * @return mixed
     * @author 11.
     */
    public function get($key = null, $default = null, $deep = false)
    {
        $input = $this->getInputSource()->all() + $this->query->all();

        return Arr::get($input, $key, $default);
    }

    /**
     * [input get方法别名]
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
     * [server 获取server]
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
     * [file 获取上传文件]
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
     * [cookie 重构cookie方法适应Facades调用]
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
     * [isJson 判断是否为json]
     *
     * @return bool
     * @author 11.
     */
    public function isJson()
    {
        return Str::contains($this->header('CONTENT_TYPE'), '/json');
    }

    /**
     * [json 获取JSON数组]
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
     * [method 获取当前请求方式]
     *
     * @return string
     * @author 11.
     */
    public function method()
    {
        return $this->getMethod();
    }


    /**
     * [ip 获取客户端IP]
     *
     * @return string
     * @author 11.
     */
    public function ip()
    {
        return $this->getClientIp();
    }

    /**
     * [ips 获取客户端所有IP]
     *
     * @return array
     * @author 11.
     */
    public function ips()
    {
        return $this->getClientIps();
    }


    /**
     * [path 获取当前pathInfo]
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
     * [root 获取根路径]
     *
     * @return string
     * @author 11.
     */
    public function root()
    {
        return rtrim($this->getSchemeAndHttpHost() . $this->getBasePath(), '/');
    }

    /**
     * [baseUrl 获取根URL]
     * @return [type] [description]
     */
    public function baseUrl()
    {
        return rtrim($this->getSchemeAndHttpHost() . $this->getBaseUrl(), '/');
    }

    /**
     * [scheme 获取请求方式]
     * @return [type] [description]
     */
    public function scheme()
    {
        return $this->isSecure() ? 'https' : 'http';
    }

    /**
     * [domain 获取当前域名]
     * @return [type] [description]
     */
    public function domain($http = false)
    {
        $host = parse_url($this->getSchemeAndHttpHost(), PHP_URL_HOST);
        return $http ? $this->scheme() .'://'.$host : $host;
    }

    /**
     * [segment 根据索引获取path]
     *
     * @param      $index [从1开始]
     * @param null $default
     * @return mixed
     * @author 11.
     */
    public function segment($index, $default = null)
    {
        return Arr::get($this->segments(), $index - 1, $default);
    }

    /**
     * [segments 分解PATH]
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
     * [secure 判断是否是安全请求]
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
     * [getInputSource 获取请求方法]
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


