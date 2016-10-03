<?php
defined('PAO') || die('The PaoCMS Load Error');

$app['log']          = true; //是否生成日志总开关
$app['debug']        = true; //debug模式
$app['token']        = 'pao_'; //网站标识
$app['timezone']     = 'PRC'; //系统时区
$app['charset']      = 'utf-8'; //系统编码
$app['language']     = 'zh-CN'; //默认语言包
$app['session']      = 'redis'; //Session存储方式

$app['dir']['pao']   = PAO;//系统根目录
$app['dir']['web']   = PAO.'/web'; //公共资源目录
$app['dir']['log']   = RUNTIME.'/Logs'; //日志存放目录
$app['dir']['file']  = 'archives'; //文件上传目录
$app['dir']['cache'] = RUNTIME.'/Cache'; //缓存存放目录
