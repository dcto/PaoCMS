<?php

namespace PAO\Exception;

use Exception;
use Illuminate\Database\QueryException;

class PAOException
{
    /**
     * 错误报告调用日志类型
     * @var array
     */
    protected $levels = array(
        1   =>  'error',
        2   =>  'warning',
        4   =>  'critical',
        8   =>  'notice',
        16  =>  'error',
        32  =>  'warning',
        64  =>  'error',
        128 =>  'warning',
        256 =>  'error',
        512 =>  'warning',
        1024=>  'notice',
        2048=>  'notice',
        4096=>  'error',
    );

    /**
     * return core exception handler
     */
    public function register()
    {
        //致命错误处理
        register_shutdown_function([__CLASS__, 'appShutdown']);

        //异常错误处理
        set_error_handler([__CLASS__, 'appError']);

        //常规异常处理
        set_exception_handler([__CLASS__, 'appException']);
    }

    /**
     * unregister exception handler
     */
    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * [Exception]
     *
     * @param Exception $e
     *
     * @author  11
     * @version v1
     *
     */
    public function Exception($e)
    {
        if (!$e instanceof \Exception) {
            $e = new ThrowableError($e);
        }
        $this->logger($e);
        $httpCode = $e->getCode()>200 ? $e->getCode() : 500;
        http_response_code($httpCode);
        if(config('app.debug')) {
            die($this->render($httpCode,$e->getMessage(),$this->debugBacktrace($e)));
        }
        die($e->getMessage());
    }

    /**
     * Handle a PHP error for the application.
     *
     * @param  int     $level
     * @param  string  $message
     * @param  string  $file
     * @param  int     $line
     * @param  array   $context
     *
     * @throws \ErrorException
     */
    public function Error($level, $message, $file = '', $line = 0, $context = array())
    {
        if (error_reporting() & $level) {
            throw new ErrorException($level, $message, $file, $line, (array) $context);
        }
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function Shutdown()
    {
        $e = error_get_last();
        if (is_array($e)) {
            $this->Exception(new \ErrorException($e['message'], $e['type'], 0, $e['file'], $e['line']));
        }
    }

    /**
     * @param $code
     * @param $e
     * @return string
     */
    public function debugBacktrace(Exception $e)
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
     * catch exception to log
     *
     * @param Exception $e
     */
    private function logException(Exception $e)
    {
        if(config('app.log')){
            if(isset($this->levels[$e->getCode()])){
                make('log')->{$this->levels[$e->getCode()]}($e->getMessage(),$this->debugBacktrace($e));
            }else{
                make('log')->alert($e->getMessage(),$this->debugBacktrace($e));
            }

            if($e instanceof QueryException) {
                //$query = str_replace(array('%', '?'), array('%%', '%s'), $e->getSql());
                //$error = vsprintf($query, $e->getBindings());
                make('log')->file('/database/'.date('Ymd'))->error($e->getMessage(), $this->debugBacktrace($e));
            }
        }
    }


    /**
     * @param $code
     * @param $message
     * @param string $debugBacktrace
     */
    private function render($code, $message, $debugBacktrace = '')
    {
        ob_end_clean();
        echo <<<EOT
<html>
<head>
 <title>Pao Frameworks Debug</title>
 <meta name="robots" content="none" />
 <style type="text/css">
 body {font: 12pt verdana; margin: 10px auto;}
 div {background: #f5f5f5; border-radius: 5px; line-height: 200%; margin-bottom: 1em; padding: 1em;}
 table {background: #aaa;}
 .bg1 {background-color: #ffc;}
 .bg2 {background-color: #eee;}
 </style>
</head>
<body>
<div id="title">{$message}</div>
EOT;
        if (!empty($debugBacktrace)) {
            echo '<div id="debug"><p><b>PAO Debug Trace (Status code: '.$code.')</b></p><table cellpadding="5" cellspacing="1" width="100%" class="table"><tbody>';
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
    }
}