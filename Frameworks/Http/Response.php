<?php

namespace PAO\Http;


use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class Response extends \Symfony\Component\HttpFoundation\Response //implements  \Illuminate\Contracts\Routing\ResponseFactory
{

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
        $response = new self($content, $status, $headers);
        $response->send();
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
        self::make($content, $status, $headers);
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
        self::make(Container::getInstance()->make('view')->render($view, $data), $status, $headers);
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
    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        if ($data instanceof \Illuminate\Contracts\Support\Arrayable && ! $data instanceof \JsonSerializable) {
            $data = $data->toArray();
        }
        $response = new JsonResponse($data, $status, $headers, $options);

        $response->send();
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
    public function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0)
    {
        return $this->json($data, $status, $headers, $options)->setCallback($callback);
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
        $response = new StreamedResponse($callback, $status, $headers);

        $response->send();
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
        $response = new BinaryFileResponse($file, 200, $headers, true, $disposition);
        if (! is_null($name)) {
             $response->setContentDisposition($disposition, $name, str_replace('%', '', \Illuminate\Support\Str::ascii($name)));
        }
        $response->send();
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
        $response = new RedirectResponse($url, $status, $headers);

        $response->send();
    }





}