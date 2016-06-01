<?php

namespace PAO\Http;


/**
PAO-Curl
实现GET、POST、UPLOAD、DOWNLOAD常用操作，支持链式写法。
示例
$curl = new Curl;
或者

$curl = Curl::init();
GET:

$curl->get(目标网址);
POST:

原生Curl是不支持POST多维数组的，本类使用post_fields_build方法实现了多维数组的提交

$curl->post(变量名, 变量值)->post(多维数组)->submit(目标网址);
UPLOAD:

$curl->post(多维数组)->upload($_FILE字段, 本地路径, 文件类型, 原始名称)->submit(目标网址);
DOWNLOAD:

$curl->download(文件地址)->save(保存路径);
配置

参考:http://php.net/manual/en/function.curl-setopt.php

$curl->set('CURLOPT_选项', 值)->post(多维数组)->submit(目标网址);
自动重试

// 出错自动重试N次(默认0)
$curl->retry(3)->post(多维数组)->submit(目标网址);
 */

class Curl {
    private $post;
    private $retry;
    private $option;
    private $default;
    private $download;
    private static $instance;

    public function __construct()
    {
        $this->retry = 0;
        $this->default = array(
            'CURLOPT_TIMEOUT'        => 30,
            'CURLOPT_ENCODING'       => '',
            'CURLOPT_IPRESOLVE'      => 1,
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_SSL_VERIFYPEER' => false,
            'CURLOPT_CONNECTTIMEOUT' => 10,
        );
    }

    /**
     * 静态实例化
     * @param string $url
     * @return array
     */
    public static function init()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * 提交GET请求
     * @param string $url
     * @return array
     */
    public function get($url)
    {
        return $this->set('CURLOPT_URL', $url)->exec();
    }

    /**
     * 设置POST信息
     * @param array|string  $data
     * @param string        $value
     * @return $this
     */
    public function post($data, $value = '')
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->post[$key] = $value;
            }
        } else {
            $this->post[$data] = $value;
        }
        return $this;
    }

    /**
     * 设置文件上传
     * @param string $field
     * @param string $path
     * @param string $type
     * @param string $name
     * @return $this
     */
    public function upload($field, $path, $type, $name)
    {
        $name = basename($name);
        if (class_exists('CURLFile')) {
            $this->set('CURLOPT_SAFE_UPLOAD', true);
            $file = curl_file_create($path, $type, $name);
        } else {
            $file = "@{$path};type={$type};filename={$name}";
        }
        return $this->post($field, $file);
    }

    /**
     * 提交POST请求
     * @param string $url
     * @return array
     */
    public function submit($url)
    {
        if (! $this->post) {
            return array(
                'error' => 1,
                'message' => '未设置POST信息'
            );
        }
        return $this->set('CURLOPT_URL', $url)->exec();
    }

    /**
     * 设置下载地址
     * @param string $url
     * @return $this
     */
    public function download($url)
    {
        $this->download = true;
        return $this->set('CURLOPT_URL', $url);
    }

    /**
     * 下载保存文件
     * @param string $path
     * @return array
     */
    public function save($path)
    {
        if (! $this->download) {
            return array(
                'error' => 1,
                'message' => '未设置下载地址'
            );
        }

        $result = $this->exec();
        if ($result['error'] === 0) {
            $fp = @fopen($path, 'w');
            fwrite($fp, $result['body']);
            fclose($fp);
        }
        return $result;
    }

    /**
     * 配置Curl操作
     * @param array|string  $item
     * @param string        $value
     * @return $this
     */
    public function set($item, $value = '')
    {
        if (is_array($item)) {
            foreach($item as $key => &$value){
                $this->option[$key] = $value;
            }
        } else {
            $this->option[$item] = $value;
        }
        return $this;
    }

    /**
     * 出错自动重试
     * @param int $times
     */
    public function retry($times = 0)
    {
        $this->retry = $times;
        return $this;
    }

    /**
     * 执行Curl操作
     * @param int $retry
     * @return array
     */
    private function exec($retry = 0)
    {
        // 初始化句柄
        $ch = curl_init();

        // 配置选项
        $options = array_merge($this->default, $this->option);
        foreach($options as $key => $val) {
            if (is_string($key)) {
                $key = constant(strtoupper($key));
            }
            curl_setopt($ch, $key, $val);
        }

        // POST选项
        if ($this->post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post_fields_build($this->post));
        }

        // 运行句柄
        $body = curl_exec($ch);
        $info = curl_getinfo($ch);

        // 检查错误
        $errno = curl_errno($ch);
        if ($errno === 0 && $info['http_code'] >= 400) {
            $errno = $info['http_code'];
        }

        // 注销句柄
        curl_close($ch);

        // 自动重试
        if ($errno && $retry < $this->retry) {
            $this->exec($retry + 1);
        }

        // 注销配置
        $this->post     = null;
        $this->retry    = null;
        $this->option   = null;
        $this->download = null;

        // 返回结果
        return array(
            'error'     => $errno ? 1 : 0,
            'message'   => $errno,
            'body'      => $body,
            'info'      => $info
        );
    }

    /**
     * 一维化POST信息
     * @param array  $input
     * @param string $pre
     * @return array
     */
    private function post_fields_build($input, $pre = null){
        if (is_array($input)) {
            $output = array();
            foreach ($input as $key => $value) {
                $index = is_null($pre) ? $key : "{$pre}[{$key}]";
                if (is_array($value)) {
                    $output = array_merge($output, $this->post_fields_build($value, $index));
                } else {
                    $output[$index] = $value;
                }
            }
            return $output;
        }
        return $input;
    }
}