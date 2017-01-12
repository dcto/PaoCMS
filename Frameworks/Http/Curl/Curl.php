<?php

namespace PAO\Http\Curl;

/**
 * An object-oriented wrapper of the PHP cURL extension.
 *
 * This library requires to have the php cURL extensions installed:
 * https://php.net/manual/curl.setup.php
 *
 * Example of making a get request with parameters:
 *
 * ```php
 * $curl = new Curl\Curl();
 * $curl->get('http://www.example.com/search', array(
 *     'q' => 'keyword',
 * ));
 * ```
 *
 * Example post request with post data:
 *
 * ```php
 * $curl = new Curl\Curl();
 * $curl->post('http://www.example.com/login/', array(
 *     'username' => 'root',
 *     'password' => 'root',
 * ));
 * ```
 *
 * @see https://php.net/manual/curl.setup.php
 */
class Curl
{
    /**
     * @var resource Contains the curl resource created by `curl_init()` function.
     */
    private $curl;

    /**
     * header
     * @var array
     */
    private $headers = array();

    /**
     * cookie
     * @var array
     */
    private $cookies = array();

    /**
     * 选项配置
     * @var array
     */
    private $options = array();

    /**
     * 超时时间
     * @var int
     */
    private $timeout = 10;

    /**
     * 重试次数
     * @var int
     */
    private $retries = 3;

    /**
     * @var string
     */
    private $userAgent = 'PAO Curl/1.1 (+https://github.com/dcto/paocms)';

    /**
     * Constructor ensures the available curl extension is loaded.
     * @throws \ErrorException
     */
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new \ErrorException('The cURL extensions is not loaded, make sure you have installed the cURL extension: https://php.net/manual/curl.setup.php');
        }
        $this->options(CURLOPT_HEADER, false);
        $this->options(CURLOPT_ENCODING, '');
        $this->options(CURLOPT_RETURNTRANSFER, true);
        $this->options(CURLOPT_TIMEOUT, $this->timeout);
        $this->options(CURLOPT_IPRESOLVE, 1);
        $this->options(CURLOPT_SSL_VERIFYPEER, FALSE);
        $this->options(CURLOPT_CONNECTTIMEOUT, $this->timeout);
        $this->options(CURLOPT_FAILONERROR, true);
        $this->options(CURLOPT_USERAGENT, $this->userAgent);
    }

    /**
     * Initializer for the curl resource.
     *
     * Is called by the __construct() of the class or when the curl request is reseted.
     */
    public function curl()
    {
        if(!is_resource($this->curl)){
            $this->curl = curl_init();
        }
        return $this->curl;
    }

    /**
     * @param $url
     * @param array $vars
     * @return Response
     * @throws \Exception
     */
    public function get($url, $vars = array())
    {
        return $this->request('GET', $url, $vars);
    }

    /**
     * @param $url
     * @param array $vars
     * @param null $encrypt
     * @return Response
     * @throws \Exception
     */
    public function post($url, $vars = array(), $encrypt = null)
    {
        return $this->request('POST', $url, $vars, $encrypt);
    }


    /**
     * Set customized curl options.
     *
     * To see a full list of options: http://php.net/curl_setopt
     *
     * @see http://php.net/curl_setopt
     * @param integer $key The curl option constant e.g. `CURLOPT_AUTOREFERER`, `CURLOPT_COOKIESESSION`
     * @param mixed $var The value to pass for the given $option.
     */
    public function options($key, $var = null)
    {
        if(is_array($key)){
            $this->options = array_merge($this->options, $key);
        }else{
            $this->options[$key] = $var;
        }
        return $this;
    }

    /**
     * Provide optional header informations.
     *
     * In order to pass optional headers by key value pairing:
     *
     * ```php
     * $curl = new Curl();
     * $curl->headers('X-Requested-With', 'XMLHttpRequest');
     * $curl->get('http://example.com/request.php');
     * ```
     *
     * @param string $key The header key.
     * @param string $var The value for the given header key.
     */
    public function headers($key, $var)
    {
        $this->headers[$key] = $key . ': ' . $var;
        return $this;
    }

    /**
     * Set contents of HTTP Cookie header.
     *
     * @param string $key The name of the cookie.
     * @param string $value The value for the provided cookie name.
     */
    public function cookies($key, $var)
    {
        $this->cookies[$key] = $var;
        $this->options(CURLOPT_COOKIE, http_build_query($this->cookies, '', '; '));
        return $this;
    }

    /**
     * Set the HTTP referer header.
     *
     * The $referer informations can help identify the requested client where the requested was made.
     *
     * @param string $referer An url to pass and will be set as referer header.
     */
    public function referer($referer)
    {
        $this->options(CURLOPT_REFERER, $referer);
        return $this;
    }

    /**
     * Provide a User Agent.
     *
     * In order to provide you cusomtized user agent name you can use this method.
     *
     * ```php
     * $curl = new Curl();
     * $curl->userAgent('My John Doe Agent 1.0');
     * $curl->get('http://example.com/request.php');
     * ```
     *
     * @param string $userAgent The name of the user agent to set for the current request.
     */
    public function userAgent($userAgent)
    {
        $this->options(CURLOPT_USERAGENT, $userAgent);
        return $this;
    }

    /**
     * Enable verbositiy.
     *
     * @param string $on
     */
    public function verbose($on = true)
    {
        $this->options(CURLOPT_VERBOSE, $on);
        return $this;
    }

    /**
     * 出错重试次数
     * @param int $times
     * @return $this
     */
    public function retry($times = 0)
    {
        $this->retries = $times;
        return $this;
    }

    /**
     * Set the associated CURL options for a request method
     *
     * @param string $method
     * @return $this
     **/
    protected function method($method) {
        switch (strtoupper($method)) {
            case 'HEAD':
                $this->options(CURLOPT_NOBODY, true);
                break;
            case 'GET':
                $this->options(CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                $this->options(CURLOPT_POST, true);
                break;
            default:
                $this->options(CURLOPT_CUSTOMREQUEST, $method);
        }
        return $this;
    }

    /**
     * @param $handle
     * @param $header
     * @return int
     */
    protected function parseHeaders($handle, $header){
        $details = explode(':', $header, 2);
        if (count($details) == 2)
        {
            $key   = trim($details[0]);
            $value = trim($details[1]);

            $headers[$key] = $value;
        }

        return strlen($header);
    }

    /**
     * Makes an HTTP request of the specified $method to a $url with an optional array or string of $vars
     *
     * Returns a CurlResponse object if the request was successful, false otherwise
     *
     * @param $method
     * @param $url
     * @param array $vars
     * @param null $encrypt
     * @return Response
     * @throws \Exception|\InvalidArgumentException
     */
    public function request($method, $url, $vars = array(), $encrypt = null) {
        if ((is_array($vars) || is_object($vars)) && $encrypt != 'multipart/form-data'){
            $vars = http_build_query($vars, '', '&');
        }
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            throw new \InvalidArgumentException('Invalid url '. $url. ' for curl request.');
        }

        $headers  = array();
        $response = false;

        $this->method($method);
        $this->options(CURLOPT_HTTPHEADER, array_values($this->headers));
        $this->options(CURLOPT_URL, $url);
        $this->options(CURLOPT_POSTFIELDS, $vars);
        $this->options(CURLOPT_HEADERFUNCTION,   function($curl, $header) use(&$headers) {
            $len    = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) return $len;
            $headers[strtolower(trim($header[0]))] = trim($header[1]);
            return $len;
        });

        curl_setopt_array($this->curl(),$this->options);

        while(($response === false) && ( -- $this->retries > 0)){
            $response = curl_exec($this->curl());
        }

        $response = new Response($headers, $response);

        $this->close();

        return $response;
    }

    /**
     * Closing the current open curl resource.
     */
    public function close()
    {
        curl_close($this->curl());
    }
}