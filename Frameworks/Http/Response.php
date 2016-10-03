<?php

namespace PAO\Http;


use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation;

class Response
{
    use Macroable;

    protected $message =  [
        //Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        //Successful 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        //Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        //Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        //Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];


    /**
     * 全局响应
     *
     * @var $response;
     */
    private $response;


    /**
     * [error Response]
     * @param $status [错误状态]
     * @param null $message [错误信息]
     * @return $this
     */
    public function error($status, $message = null)
    {
        $message = $message?:$this->message[$status];
        $this->response = new HttpFoundation\Response($message, $status);
        return $this;
    }


    /**
     * [make Response]
     *
     * @param string $content [响应x内容]
     * @param int    $status [状态值]
     * @param array  $headers [header]
     * @author 11.
     */
    public function make($content = '', $status = 200 , array $headers = [])
    {
        $this->response = new HttpFoundation\Response($content, $status, $headers);
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
    public function view($view, $data = [], $status = 200, array $headers = [])
    {
       return $this->make(Container::getInstance()->make('view')->render($view, $data), $status, $headers);
    }

    /**
     * [json Json格式响应]
     *
     * @param array $data [传入值]
     * @param int   $status [状态值]
     * @param array $headers [header]
     * @param int   $options [其他设置]
     * @return HttpFoundation\JsonResponse
     * @author 11.
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        if ($data instanceof Arrayable && ! $data instanceof \JsonSerializable) {
            $data = $data->toArray();
        }
        $this->response = new HttpFoundation\JsonResponse($data, $status, $headers);

        return $this->response;
    }


    /**
     * [jsonp Jsonp响应格式]
     *
     * @param array $data [传入值]
     * @param int   $status [状态值]
     * @param array $headers [header]
     * @param int   $options [其他设置]
     * @return HttpFoundation\JsonResponse
     * @author 11.
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0)
    {
        return $this->json($data, $status, $headers)->setCallback($callback);
    }

    /**
     * [redirect 跳转 别名]
     *
     * @param       $url [地址]
     * @param int   $status [状态值]
     * @param array $headers [header]
     * @return HttpFoundation\RedirectResponse
     * @author 11.
     */
    public function url($url, $status = 302, array $headers = [])
    {
        return $this->redirect($url, $status, $headers);
    }

    /**
     * [stream 数据流响应]
     *
     * @param \Closure $callback [回调]
     * @param int      $status [状态值]
     * @param array    $headers [header]
     * @return HttpFoundation\StreamedResponse
     * @author 11.
     */
    public function stream($callback, $status = 200, array $headers = [])
    {
        $this->response = new HttpFoundation\StreamedResponse($callback, $status, $headers);

        return $this->response;
    }


    /**
     * [download 响应下载]
     *
     * @param \SplFileInfo|string $file [文件地址]
     * @param null                $name [文件名]
     * @param array               $headers header
     * @param string              $disposition
     * @return HttpFoundation\BinaryFileResponse
     * @author 11.
     */
    public function download($file, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $this->response = new HttpFoundation\BinaryFileResponse($file, 200, $headers, true, $disposition);
        if (! is_null($name)) {
             $this->response->setContentDisposition($disposition, $name, str_replace('%', '', \Illuminate\Support\Str::ascii($name)));
        }
        return $this->response;
    }


    /**
     * [redirect 跳转]
     *
     * @param       $url [地址]
     * @param int   $status [状态值]
     * @param array $headers [header]
     * @return HttpFoundation\RedirectResponse
     * @author 11.
     */
    public function redirect($url, $status = 302, $headers = [])
    {
        $this->response = new HttpFoundation\RedirectResponse($url, $status, $headers);

        return $this->response;
    }

    /**
     * [__call]
     *
     * @param $method
     * @param $parameters
     * @author 11.
     */
    public function __call($method, $parameters)
    {
        if(!$this->response instanceof HttpFoundation\Response)
        {
            $this->response = new HttpFoundation\Response;
        }

        return call_user_func_array(array($this->response, $method), $parameters);
    }

    /**
     * 操作父类属性
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->response->$name = $value;
    }

    /**
     * 获取父类属性
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->response->$name;
    }

}
