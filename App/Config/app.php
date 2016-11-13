<?php
defined('PAO') || die('The PaoCMS Load Error');

$app['log']          = 1; //是否生成日志总开关

$app['debug']        = 1; //debug模式

$app['token']        = 'pao_'; //网站标识

$app['cache']        = 'redis'; //缓存引擎

$app['charset']      = 'utf-8'; //系统编码

$app['timezone']     = 'PRC'; //系统时区

$app['language']     = 'zh-CN'; //默认语言包


$app['dir']['log']   = '/RunTime/Logs';

$app['dir']['cache']   = '/RunTime/Cache';
