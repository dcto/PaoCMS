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

        if(config('app.log')){
            if(isset($this->levels[$e->getCode()])){
                make('log')->{$this->levels[$e->getCode()]}($e->getMessage(),$this->debugBacktrace($e));
            }else{
                make('log')->alert($e->getMessage(),$this->debugBacktrace($e));
            }

            if($e instanceof QueryException) {
                $sql = str_replace(array('%', '?'), array('%%', '%s'), $e->getSql());
                $error = vsprintf($sql, $e->getBindings());
                make('log')->file('/Query/'.date('Ymd').'_error')->error($error);
            }
        }
        $httpCode = $e->getCode()>200 ? $e->getCode() : 500;
        http_response_code($httpCode);
        if(config('app.debug')) {
            die($this->display($httpCode,$e->getMessage(),$this->debugBacktrace($e)));
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
    public function handleError($level, $message, $file = '', $line = 0, $context = array())
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, $level, $level, $file, $line);
        }
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown()
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
    public function debugBacktrace($e)
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
     * @static
     * @access public
     * @param string $type db,system
     * @param string $errorMsg
     * @param string $phpMsg
     */
    public function display($code, $message, $debugBacktrace = '')
    {
        ob_end_clean();
        $content = <<<EOT
<!DOCTYPE html">
<html>
<head>
 <title>Pao Frameworks Debug</title>
 <meta charset="utf-8" />
 <meta name="robots" content="none" />
 <style type="text/css">
 <!--
 body {font: 12pt verdana; margin: 10px;}
 h3 {color: #f00; font-weight: normal}
 div {background: #f5f5f5; border-radius: 5px; line-height: 200%; margin-bottom: 1em; padding: 1em;}
 table {background: #aaa;}
 .bg1 {background-color: #ffc;}
 .bg2 {background-color: #eee;}
 -->
 </style>
</head>
<body>
<h3>Status Code: $code</h3>
<div>{$message}</div>
EOT;
            if (!empty($debugBacktrace)) {
                $content .= '<div class="title">';
                $content .= '<p><strong>PAO Debug Trace</strong></p>';
                $content .= '<table cellpadding="5" cellspacing="1" width="100%" class="table"><tbody>';
                if (is_array($debugBacktrace)) {
                    $content .= '<tr class="bg2"><td>No.</td><td>File</td><td>Line</td><td>Code</td></tr>';
                    foreach ($debugBacktrace as $k => $error) {
                        $k++;
                        $content .= '<tr class="bg1">';
                        $content .= '<td>' . $k . '</td>';
                        $content .= '<td>' . $error['file'] . '</td>';
                        $content .= '<td>' . $error['line'] . '</td>';
                        $content .= '<td>' . $error['function'] . '</td>';
                        $content .= '</tr>';
                    }
                } else {
                    $content .= '<tr><td><ul>' . $debugBacktrace . '</ul></td></tr>';
                }
                $content .= '</tbody></table></div>';
            }

        $content .= <<<EOT
</div>
</body>
</html>
EOT;
        return $content;
    }
}