<?php

namespace PAO\Http;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\ParameterBag;

class Request extends HttpFoundation\Request
{

    /**
     * 容器
     * @var static
     */
    private $container;


    /**
     * 临时存储文件
     * @var
     */
    private $gainFiles;


    /**
     * 重构Request方法
     */
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        /**
         * 获取容器
         */
        $this->container = Container::getInstance();

        /**
         * 命令行模式获取参数
         */
        if ('cli-server' === php_sapi_name()) {
            if (array_key_exists('HTTP_CONTENT_LENGTH', $_SERVER)) {
                $_SERVER['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
            }
            if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
                $_SERVER['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
            }
        }

        /**
         * 初始化请求对象
         */
        parent::__construct(
            array_merge($_GET, $query),
            array_merge($_POST, $request),
            array_merge(array('system'=>'PaoCMS'), $attributes),
            array_merge($_COOKIE, $cookies),
            array_merge($_FILES, $files),
            array_merge($_SERVER, $server)
        );

        /**
         * 后置处理
         */
        if (0 === strpos($this->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded') && in_array(strtoupper($this->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))) {
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
     * [获取当前 URL]
     * @param null $cast [构建 URL 参数 @=获得路由, $=根据当前url,获取控制器url,]
     * @example url('@index');
     * @example url('$controller');
     * @example url('/index/abc');
     * @example url();
     * @return string
     */
    public function url($cast = null)
    {
        if(is_null($cast)) return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');

        $baseUrl = trim($this->baseUrl(), '/').'/';
        if($cast[0]=='@') {
            $route = $this->container->make('route')->get(ltrim($cast, '@'));
            return $baseUrl.trim($route, '/');
        }else if($cast[0]=='$') {
            $route = $this->container->make('route');
            $url = str_replace(array('$controller', '$action'), array($route->getController(), $route->getAction()), $cast);
            return $baseUrl.trim($url,'/');
        }else{
            return $baseUrl.trim($cast, '/');
        }
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
                $queryString = array_key_exists($cast, $queryString) ? array($cast=>$queryString[$cast]) : array();
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
    public function all($do = null)
    {
        if($do[0]=='!') {
            return $this->not(ltrim($do,'!'));
        }
        return array_replace_recursive($this->input(), $this->files->all());
    }

    /**
     * [not 排除返回]
     * @param $key
     * @return array
     */
    public function not($key = null)
    {
        //return array_diff_key($this->all(), array_fill_keys($key, null));
        return Arr::except($this->all(), $key);
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
     * [take get方法加强版,支持数组]
     * @param $key
     * @return array|mixed
     */
    public function take($key)
    {
        if(is_array($key)){
            return array_intersect_key($this->all(), array_fill_keys($key, null));
        }else{
            return $this->get($key);
        }
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
        return Arr::get($this->files(), $key, $default);
    }


    /**
     * [files 获取多文件]
     * @return mixed
     */
    public function files()
    {
        $files = $this->files->all();
        return $this->gainFiles
            ? $this->gainFiles
            : $this->gainFiles = $this->gainFiles($files);
    }


    /**
     * @param array $files
     * @return array
     */
    private function gainFiles(array $files)
    {
        return array_map(function ($file) {
            if (is_null($file) || (is_array($file) && empty(array_filter($file)))) {
                return $file;
            }

            return is_array($file)
                ? $this->gainFiles($file)
                : Upload::initialize($file);
        }, $files);
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


