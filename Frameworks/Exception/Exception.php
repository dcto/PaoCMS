<?php

namespace PAO\Exception;


class Exception
{
    /**
     * 备用内存大小
     * @var int
     */
    private $memory = 262144;

    /**
     * 注册异常拦截
     */
    public function register()
    {
        define('E_FATAL',  E_ERROR | E_USER_ERROR |  E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR| E_PARSE );

        //错误级别
        error_reporting(E_ALL);

        //开启错误
        ini_set('display_errors', 'On');

        //预留内存
        $this->memory && str_repeat('*', $this->memory);

        //截获未捕获的异常
        set_exception_handler(array($this, 'handleException'));

        //截获各种错误 此处切不可掉换位置
        set_error_handler(array($this, 'handleError'));

        //截获致命性错误
        register_shutdown_function(array($this, 'handleFatalError'));
    }

    /**
     * 注销异常拦截
     */
    public function restore()
    {
        restore_error_handler();
        restore_exception_handler();
    }


    /**
     * 处理截获的未捕获的异常
     * @param $e
     */
    public function handleException($e)
    {
        $this->restore();
        $this->logException($e)->display($e);
    }

    /**
     * 捕获常规错误
     *
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @return bool|void
     */
    public function handleError($code, $message, $file, $line)
    {
        unset($this->memory);
        //将错误变成异常抛出 统一交给异常处理函数进行处理
        if(error_reporting() & $code) {
            return $this->logException($e = new E($message, $code, $code, $file, $line))->display($e);
        }
        return false;
    }

    /**
     * 截获致命性错误
     */
    public function handleFatalError()
    {
        //释放备用内存供下面处理程序使用
        unset($this->memory);
        //最后一条错误信息
        $e = error_get_last();
        //如果是致命错误进行处理
        if(E::isFatalError($e)){
            return $this->logException($e = new E($e['message'], $e['type'], $e['type'], $e['file'], $e['line']))->display($e);
        }
        return true;
    }


    /**
     * 获取异常调用
     *
     * @param $code
     * @param $e
     * @return string
     */
    final private function debugBacktrace(\Exception $e)
    {
        $trace = $e->getTrace();
        krsort($trace);
        $trace[] = array('file' => $e->getFile(), 'line' => $e->getLine(), 'function' => 'break');
        $traces = array();

        foreach ($trace as $error) {
            if (!empty($error['function'])) {
                $fun = '';
                if (!empty($error['class'])) {
                    $fun .= $error['class'] . $error['type'];
                }
                $fun .= $error['function'] . '(';
                if (!empty($error['args'])) {
                    $mark = '';
                    foreach ($error['args'] as $arg) {
                        $fun .= $mark;
                        if (is_array($arg)) {
                            $fun .= 'Array';
                        } elseif (is_bool($arg)) {
                            $fun .= $arg ? 'true' : 'false';
                        } elseif (is_int($arg)) {
                            $fun .= (defined('SITE_DEBUG') && SITE_DEBUG) ? $arg : '%d';
                        } elseif (is_float($arg)) {
                            $fun .= (defined('SITE_DEBUG') && SITE_DEBUG) ? $arg : '%f';
                        } else {
                            $fun .= (defined('SITE_DEBUG') && SITE_DEBUG) ? '\'' . htmlspecialchars(substr(self::clear($arg), 0, 10)) . (strlen($arg) > 10 ? ' ...' : '') . '\'' : '%s';
                        }
                        $mark = ', ';
                    }
                }
                $fun .= ')';
                $error['function'] = $fun;
            }
            if (!isset($error['line'])) {
                continue;
            }
            $traces[] = array('file' => str_replace(array(PAO, ''), array('', '/'), $error['file']), 'line' => $error['line'], 'function' => $error['function']);
        }
        return $traces;
    }

    /**
     * 记录异常信息s
     * @param \Exception $e
     */
    final private function logException(\Exception $e)
    {
        if(config('app.log')){
            try{
                $_ERROR = array(
                    '[TIME]'       =>     date('Y-m-d H:i:s'),
                    '[CODE]'       =>     E::codes($e->getCode()),
                    '[FILE]'       =>     $e->getFile(),
                    '[LINE]'       =>     $e->getLine(),
                    '[MESSAGE]'    =>     E::error($e->getCode()).' '.$e->getMessage(),
                    '[METHOD]'     =>     $_SERVER['REQUEST_METHOD'],
                    '[REMOTE]'     =>     $_SERVER["REMOTE_ADDR"],
                    '[REQUEST]'    =>     'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"],
                    '[COOKIE]'     =>     $_SERVER['HTTP_COOKIE'],
                    '[BACKTRACE]'  =>     PHP_EOL.$e->getTraceAsString()
                );
                array_walk($_ERROR, function (&$v, $k) { $v = $k.' '.$v;});
                if(!is_dir($logs = path(config('dir.logs')).'/exception/')){
                    mkdir($logs, 0777, true);
                }
                $logs = $logs.APP.'_'.date('Ymd').'.log';
                file_put_contents($logs, implode(PHP_EOL, $_ERROR).PHP_EOL.str_repeat('=',100).PHP_EOL.PHP_EOL, FILE_APPEND);
            }catch (\Exception $e){
                return $this->display($e);
            }
        }
        return $this;
    }


    /**
     * display exception
     *
     * @param $e
     */
    final private function display(\Exception $e)
    {
        http_response_code(500);
        config('app.debug') || die('Server Error');

        $debugBacktrace = $this->debugBacktrace($e);
        echo '<html><head><title>Pao Frameworks Debug</title><meta name="robots" content="none" /><style type="text/css">body {font: 12pt verdana; margin: 10px auto;}div {background: #f5f5f5; border-radius: 5px; line-height: 200%; margin-bottom: 1em; padding: 1em;}table {background: #aaa;}.bg1 {background-color: #ffc;}.bg2 {background-color: #eee;}</style></head><body><div id="title"><b>'.E::error($e->getCode()).'</b>: '.$e->getMessage().'</div>';
        if ($debugBacktrace) {
            echo '<div id="debug"><p><b>Debug Backtrace</b></p><table cellpadding="5" cellspacing="1" width="100%" class="table"><tbody>';
            if (is_array($debugBacktrace)) {
                echo '<tr class="bg2"><td>No.</td><td>File</td><td>Line</td><td>Code</td></tr>';
                foreach ($debugBacktrace as $k => $error) {
                    $k++;
                    echo "<tr class=\"bg1\"><td>{$k}</td><td>{$error['file']}</td><td>{$error['line']}</td><td>{$error['function']}</td></tr>";
                }
            }else{
                echo '<tr><td><ul>' . $debugBacktrace . '</ul></td></tr>';
            }
            echo '</tbody></table></div>';
        }
        echo '</body></html>';
        die;
    }
}