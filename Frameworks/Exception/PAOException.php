<?php

namespace PAO\Exception;


use Exception;
use ReflectionMethod;
use ReflectionFunction;
use Illuminate\Container\Container;

class PAOException
{
    protected $container;

    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    /**
     * [Exception �쳣���]
     *
     * @param Exception $e [�쳣����]
     *
     * @author  11
     * @version v1
     *
     */
    public function Exception($e)
    {
        if($this->container->config('config.log')){
            $this->container->make('log')->error($e);
        }
        $httpCode = $e->getCode()>200 ? $e->getCode() : 500;
        http_response_code($httpCode);
        if($this->container->config('config.debug')) {
            die($this->HandleError($e));
        }
        die($e->getMessage());
    }

    public function HandleError($e)
    {
        $type = 'system';
        $message = $e->getMessage();
        $trace = $e->getTrace();
        krsort($trace);
        $trace[] = array('file' => $e->getFile(), 'line' => $e->getLine(), 'function' => 'break');
        $errorExplain = array();

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
            $errorExplain[] = array('file' => str_replace(array(PAO, ''), array('', '/'), $error['file']), 'line' => $error['line'], 'function' => $error['function']);
        }
        return $this->display($type, $message, $errorExplain);

    }


    /**
     * [debugBacktrace ����ִ�й��̻�����Ϣ]
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
                // ���÷���API����ȡ����/�������ڵ��ļ�������
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
     * ��ʾ����
     *
     * @static
     * @access public
     * @param string $type �������� db,system
     * @param string $errorMsg
     * @param string $phpMsg
     */
    public function display($type, $message, $errorExplain = '')
    {
        //ob_end_clean();
        $title = $type == 'db' ? 'Database' : 'System';
        $content = <<<EOT
<!DOCTYPE html">
<html>
<head>
 <title>{$_SERVER['REQUEST_URI']} - {$title} Error</title>
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
<h1>{$title} Error</h1>
<div class='info'>{$message}</div>
EOT;
            if (!empty($errorExplain)) {
                $content .= '<div class="info">';
                $content .= '<p><strong>PAO Debug</strong></p>';
                $content .= '<table cellpadding="5" cellspacing="1" width="100%" class="table"><tbody>';
                if (is_array($errorExplain)) {
                    $content .= '<tr class="bg2"><td>No.</td><td>File</td><td>Line</td><td>Code</td></tr>';
                    foreach ($errorExplain as $k => $error) {
                        $k++;
                        $content .= '<tr class="bg1">';
                        $content .= '<td>' . $k . '</td>';
                        $content .= '<td>' . $error['file'] . '</td>';
                        $content .= '<td>' . $error['line'] . '</td>';
                        $content .= '<td>' . $error['function'] . '</td>';
                        $content .= '</tr>';
                    }
                } else {
                    $content .= '<tr><td><ul>' . $errorExplain . '</ul></td></tr>';
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