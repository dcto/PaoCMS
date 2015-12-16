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
     * [make Response��Ӧ]
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
     * [show make����]
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
     * [view ��ģ����Ӧ]
     *
     * @param array $data ��������
     * @param int   $status ��Ӧ״̬
     * @param array $headers ͷ����Ӧ
     * @author 11.
     */
    public function view($view, array $data = [], $status = 200, array $headers = [])
    {
        self::make(Container::getInstance()->make('view')->render($view, $data), $status, $headers);
    }

    /**
     * [json Json��ʽ��Ӧ]
     *
     * @param array $data ��������
     * @param int   $status ��Ӧ״̬
     * @param array $headers ͷ����Ӧ
     * @param int   $options ��ز���
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
     * [jsonp Jsonp��ʽ��Ӧ]
     *
     * @param array $data ��������
     * @param int   $status ��Ӧ״̬
     * @param array $headers ͷ����Ӧ
     * @param int   $options ��ز���
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @author 11.
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0)
    {
        return $this->json($data, $status, $headers, $options)->setCallback($callback);
    }


    /**
     * [stream ���ݿ��ʽ��Ӧ]
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
     * [download �ļ�����]
     *
     * @param \SplFileInfo|string $file �����ļ�
     * @param null                $name �ļ���
     * @param array               $headers ͷ��
     * @param string              $disposition ����
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
     * [redirect ��Ӧ��ת]
     *
     * @param       $url ��ת��ַ
     * @param int   $status ״̬
     * @param array $headers ͷ��
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @author 11.
     */
    public function redirect($url, $status = 302, $headers = [])
    {
        $response = new RedirectResponse($url, $status, $headers);

        $response->send();
    }





}