<?php

namespace PAO\Http;



class Response  //implements  \Illuminate\Contracts\Routing\ResponseFactory
{

    /**
     * 当前响应方法
     *
     * @var $response;
     */
    private $response;


    /**
     * [__call 方法响应]
     *
     * @param $method
     * @param $parameters
     * @author 11.
     */
    public function __call($method, $parameters)
    {
        /**
         * 判断是否已实例化对象
         */
        if(!$this->response instanceof \Symfony\Component\HttpFoundation\Response)
        {
            $this->response = new \Symfony\Component\HttpFoundation\Response;
        }

        return call_user_func_array(array($this->response, $method), $parameters);
    }



    /**
     * [make Response响应]
     *
     * @param string $content
     * @param int    $status
     * @param array  $headers
     * @author 11.
     */
    public function make($content = '', $status = 200 , array $headers = [])
    {
        $this->response = new \Symfony\Component\HttpFoundation\Response($content, $status, $headers);

        return $this;
    }


    /**
     * [show make别名]
     *
     * @param string $content
     * @param int    $status
     * @param array  $headers
     * @author 11.
     */
    public function show($content = '', $status = 200 , array $headers = [])
    {
       return $this->make($content, $status, $headers);
    }


    /**
     * [view 带模板响应]
     *
     * @param array $data 数据数组
     * @param int   $status 响应状态
     * @param array $headers 头部响应
     * @author 11.
     */
    public function view($view, array $data = [], $status = 200, array $headers = [])
    {
       return $this->make(\Illuminate\Container\Container::getInstance()->make('view')->render($view, $data), $status, $headers);
    }

    /**
     * [json Json格式响应]
     *
     * @param array $data 数据数组
     * @param int   $status 响应状态
     * @param array $headers 头部响应
     * @param int   $options 相关参数
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @author 11.
     */
    public function json($data = [], $status = 200, array $headers = [])
    {
        if ($data instanceof \Illuminate\Contracts\Support\Arrayable && ! $data instanceof \JsonSerializable) {
            $data = $data->toArray();
        }
        $this->response = new \Symfony\Component\HttpFoundation\JsonResponse($data, $status, $headers);

        return $this;
    }


    /**
     * [jsonp Jsonp格式响应]
     *
     * @param array $data 数据数组
     * @param int   $status 响应状态
     * @param array $headers 头部响应
     * @param int   $options 相关参数
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @author 11.
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = [])
    {
        return $this->json($data, $status, $headers)->setCallback($callback);
    }


    /**
     * [stream 数据库格式响应]
     *
     * @param \Closure $callback
     * @param int      $status
     * @param array    $headers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @author 11.
     */
    public function stream($callback, $status = 200, array $headers = [])
    {
        $this->response = new \Symfony\Component\HttpFoundation\StreamedResponse($callback, $status, $headers);

        return $this;
    }


    /**
     * [download 文件下载]
     *
     * @param \SplFileInfo|string $file 下载文件
     * @param null                $name 文件名
     * @param array               $headers 头部
     * @param string              $disposition 描术
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @author 11.
     */
    public function download($file, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $this->response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($file, 200, $headers, true, $disposition);
        if (! is_null($name)) {
             $this->response->setContentDisposition($disposition, $name, str_replace('%', '', \Illuminate\Support\Str::ascii($name)));
        }
        return $this;
    }


    /**
     * [redirect 响应跳转]
     *
     * @param       $url 跳转网址
     * @param int   $status 状态
     * @param array $headers 头部
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @author 11.
     */
    public function redirect($url, $status = 302, $headers = [])
    {
        $this->response = new \Symfony\Component\HttpFoundation\RedirectResponse($url, $status, $headers);

        return $this;
    }

}