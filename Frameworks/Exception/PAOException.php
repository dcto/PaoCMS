<?php

namespace PAO\Exception;


use Exception;
use ReflectionMethod;
use ReflectionFunction;
use Illuminate\Container\Container;

class PAOException
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * [Exception 异常输出]
     *
     * @param Exception $e [异常对象]
     *
     * @author  11#pao11.com
     * @version v1
     *
     */
    public function Exception($e)
    {
        if($this->container->config('config.log')){
            $this->container->make('log')->error($e);
        }

        if($this->container->config('config.debug')) {
            $HttpCode = method_exists($e, 'getHttpCode') ? $e->getHttpCode() : 500;
            die($this->HandleError($e, $HttpCode));
        }
    }

    public function HandleError($e)
    {
        $type = 'system';
        $errorMsg = $e->getMessage();
        $trace = $e->getTrace();
        krsort($trace);
        $trace[] = array('file' => $e->getFile(), 'line' => $e->getLine(), 'function' => 'break');
        $phpMsg = array();

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
            $phpMsg[] = array('file' => str_replace(array(PAO, ''), array('', '/'), $error['file']), 'line' => $error['line'], 'function' => $error['function']);
        }
        return self::showError($type, $errorMsg, $phpMsg);

    }


    /**
     * [debugBacktrace 代码执行过程回溯信息]
     *
     *
     */
    public static function debugBacktrace()
    {
        $skipFunc[] = 'Error->debugBacktrace';

        $show = $log = '';
        $debugBacktrace = debug_backtrace();
        ksort($debugBacktrace);
        foreach ($debugBacktrace as $k => $error) {
            if (!isset($error['file'])) {
                // 利用反射API来获取方法/函数所在的文件和行数
                try {
                    if (isset($error['class'])) {
                        $reflection = new ReflectionMethod($error['class'], $error['function']);
                    } else {
                        $reflection = new ReflectionFunction($error['function']);
                    }
                    $error['file'] = $reflection->getFileName();
                    $error['line'] = $reflection->getStartLine();
                } catch (Exception $e) {
                    continue;
                }
            }

            $file = str_replace(PAO, '', $error['file']);
            $func = isset($error['class']) ? $error['class'] : '';
            $func .= isset($error['type']) ? $error['type'] : '';
            $func .= isset($error['function']) ? $error['function'] : '';
            if (in_array($func, $skipFunc)) {
                break;
            }
            $error['line'] = sprintf('%04d', $error['line']);

            $show .= '<li>[Line: ' . $error['line'] . ']' . $file . '(' . $func . ')</li>';
            $log .= !empty($log) ? ' -> ' : '';
            $log .= $file . ':' . $error['line'];
        }
        return array($show, $log);
    }


    /**
     * 显示错误
     *
     * @static
     * @access public
     * @param string $type 错误类型 db,system
     * @param string $errorMsg
     * @param string $phpMsg
     */
    public static function showError($type, $errorMsg, $phpMsg = '')
    {
        //ob_end_clean();
        $host = $_SERVER['HTTP_HOST'];
        $title = $type == 'db' ? 'Database' : 'System';
        $content = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
 <title>$host - $title Error</title>
 <meta http-equiv="Content-Type" content="text/html; charset="utf-8" />
 <meta name="robots" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
 <style type="text/css">
 <!--
 body { background-color: white; color: black; font: 9pt/11pt verdana, arial, sans-serif;}
 #container {margin: 10px;}
 #message {width: 1024px; color: black;}
 .red {color: red;}
 a:link {font: 9pt/11pt verdana, arial, sans-serif; color: red;}
 a:visited {font: 9pt/11pt verdana, arial, sans-serif; color: #4e4e4e;}
 h1 {color: #FF0000; font: 18pt "Verdana"; margin-bottom: 0.5em;}
 .bg1 {background-color: #FFFFCC;}
 .bg2 {background-color: #EEEEEE;}
 .table {background: #AAAAAA; font: 11pt Menlo,Consolas,"Lucida Console"}
 .info {
  background: none repeat scroll 0 0 #F3F3F3;
  border: 0px solid #aaaaaa;
  border-radius: 10px 10px 10px 10px;
  color: #000000;
  font-size: 11pt;
  line-height: 160%;
  margin-bottom: 1em;
  padding: 1em;
 }

 .help {
  background: #F3F3F3;
  border-radius: 10px 10px 10px 10px;
  font: 12px verdana, arial, sans-serif;
  text-align: center;
  line-height: 160%;
  padding: 1em;
 }

 .sql {
  background: none repeat scroll 0 0 #FFFFCC;
  border: 1px solid #aaaaaa;
  color: #000000;
  font: arial, sans-serif;
  font-size: 9pt;
  line-height: 160%;
  margin-top: 1em;
  padding: 4px;
 }
 -->
 </style>
</head>
<body>
<div id="container">
<h1>$title Error</h1>
<div class='info'>$errorMsg</div>
EOT;


            if (!empty($phpMsg)) {
                $content .= '<div class="info">';
                $content .= '<p><strong>PaoCMS Debug</strong></p>';
                $content .= '<table cellpadding="5" cellspacing="1" width="100%" class="table"><tbody>';
                if (is_array($phpMsg)) {
                    $content .= '<tr class="bg2"><td>No.</td><td>File</td><td>Line</td><td>Code</td></tr>';
                    foreach ($phpMsg as $k => $msg) {
                        $k++;
                        $content .= '<tr class="bg1">';
                        $content .= '<td>' . $k . '</td>';
                        $content .= '<td>' . $msg['file'] . '</td>';
                        $content .= '<td>' . $msg['line'] . '</td>';
                        $content .= '<td>' . $msg['function'] . '</td>';
                        $content .= '</tr>';
                    }
                } else {
                    $content .= '<tr><td><ul>' . $phpMsg . '</ul></td></tr>';
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