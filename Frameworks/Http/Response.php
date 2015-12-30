<?php

namespace PAO\Http;



class Response  //implements  \Illuminate\Contracts\Routing\ResponseFactory
{

    /**
     * ��ǰ��Ӧ����
     *
     * @var $response;
     */
    private $response;


    /**
     * [__call ������Ӧ]
     *
     * @param $method
     * @param $parameters
     * @author 11.
     */
    public function __call($method, $parameters)
    {
        /**
         * �ж��Ƿ���ʵ��������
         */
        if(!$this->response instanceof \Symfony\Component\HttpFoundation\Response)
        {
            $this->response = new \Symfony\Component\HttpFoundation\Response;
        }

        return call_user_func_array(array($this->response, $method), $parameters);
    }



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
        $this->response = new \Symfony\Component\HttpFoundation\Response($content, $status, $headers);

        return $this;
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
       return $this->make($content, $status, $headers);
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
       return $this->make(\Illuminate\Container\Container::getInstance()->make('view')->render($view, $data), $status, $headers);
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
    public function json($data = [], $status = 200, array $headers = [])
    {
        if ($data instanceof \Illuminate\Contracts\Support\Arrayable && ! $data instanceof \JsonSerializable) {
            $data = $data->toArray();
        }
        $this->response = new \Symfony\Component\HttpFoundation\JsonResponse($data, $status, $headers);

        return $this;
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
    public function jsonp($callback, $data = [], $status = 200, array $headers = [])
    {
        return $this->json($data, $status, $headers)->setCallback($callback);
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
        $this->response = new \Symfony\Component\HttpFoundation\StreamedResponse($callback, $status, $headers);

        return $this;
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
        $this->response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($file, 200, $headers, true, $disposition);
        if (! is_null($name)) {
             $this->response->setContentDisposition($disposition, $name, str_replace('%', '', \Illuminate\Support\Str::ascii($name)));
        }
        return $this;
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
        $this->response = new \Symfony\Component\HttpFoundation\RedirectResponse($url, $status, $headers);

        return $this;
    }

}