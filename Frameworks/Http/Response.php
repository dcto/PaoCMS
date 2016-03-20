<?php

namespace PAO\Http;



class Response //implements  \Illuminate\Contracts\Routing\ResponseFactory
{

    /**
     * 全局响应
     *
     * @var $response;
     */
    private $response;


    /**
     * [__call]
     *
     * @param $method
     * @param $parameters
     * @author 11.
     */
    public function __call($method, $parameters)
    {
        if(!$this->response instanceof \Symfony\Component\HttpFoundation\Response)
        {
            $this->response = new \Symfony\Component\HttpFoundation\Response;
        }

        return call_user_func_array(array($this->response, $method), $parameters);
    }



    /**
     * [make Response]
     *
     * @param string $content [响应内容]
     * @param int    $status [状态值]
     * @param array  $headers [header]
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
     * [view 视图响应]
     *
     * @param array $data [传入值]
     * @param int   $status [状在值]
     * @param array $headers [header]
     * @author 11.
     */
    public function view($view, array $data = [], $status = 200, array $headers = [])
    {
       return $this->make(\Illuminate\Container\Container::getInstance()->make('view')->render($view, $data), $status, $headers);
    }

    /**
     * [json Json格式响应]
     *
     * @param array $data [传入值]
     * @param int   $status [状态值]
     * @param array $headers [header]
     * @param int   $options [其他设置]
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
     * [jsonp Jsonp响应格式]
     *
     * @param array $data [传入值]
     * @param int   $status [状态值]
     * @param array $headers [header]
     * @param int   $options [其他设置]
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @author 11.
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = [])
    {
        return $this->json($data, $status, $headers)->setCallback($callback);
    }


    /**
     * [stream 数据流响应]
     *
     * @param \Closure $callback [回调]
     * @param int      $status [状态值]
     * @param array    $headers [header]
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @author 11.
     */
    public function stream($callback, $status = 200, array $headers = [])
    {
        $this->response = new \Symfony\Component\HttpFoundation\StreamedResponse($callback, $status, $headers);

        return $this;
    }


    /**
     * [download 响应下载]
     *
     * @param \SplFileInfo|string $file [文件地址]
     * @param null                $name [文件名]
     * @param array               $headers header
     * @param string              $disposition
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
     * [redirect 跳转]
     *
     * @param       $url [地址]
     * @param int   $status [状态值]
     * @param array $headers [header]
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @author 11.
     */
    public function redirect($url, $status = 302, $headers = [])
    {
        $this->response = new \Symfony\Component\HttpFoundation\RedirectResponse($url, $status, $headers);

        return $this;
    }

}
