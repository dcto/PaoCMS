<?php

namespace PAO\Http;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class Response extends \Symfony\Component\HttpFoundation\Response implements  \Illuminate\Contracts\Routing\ResponseFactory
{



    /**
     * [make 普通响应]
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
        return  self::make($content, $status, $headers);
    }


    /**
     * [view 带模板响应]
     *
     * @param       $view
     * @param array $data
     * @param int   $status
     * @param array $headers
     * @author 11.
     */
    public function view($view, $data = [], $status = 200, array $headers = [])
    {

    }

    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        if ($data instanceof \Illuminate\Contracts\Support\Arrayable && ! $data instanceof \JsonSerializable) {
            $data = $data->toArray();
        }
        return new JsonResponse($data, $status, $headers, $options);
    }

    public function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0)
    {
        return $this->json($data, $status, $headers, $options)->setCallback($callback);
    }



    public function stream($callback, $status = 200, array $headers = [])
    {
        return new StreamedResponse($callback, $status, $headers);
    }



    public function download($file, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $response = new BinaryFileResponse($file, 200, $headers, true, $disposition);
        if (! is_null($name)) {
            return $response->setContentDisposition($disposition, $name, str_replace('%', '', \Illuminate\Support\Str::ascii($name)));
        }
        return $response;
    }



    public function redirectTo($path, $status = 302, $headers = [], $secure = null){}


    public function redirectToRoute($route, $parameters = [], $status = 302, $headers = []){}

    public function redirectToAction($action, $parameters = [], $status = 302, $headers = []){}

    public function redirectGuest($path, $status = 302, $headers = [], $secure = null){}

    public function redirectToIntended($default = '/', $status = 302, $headers = [], $secure = null){}


}