<?php
defined('PAO') || die('The PaoCMS Load Error');

return array(
        'debug' => true, //debug模式
        'log' => true, //是否生成日志总开关
        'token'=> 'pao_', //网站标识
        'timezone'=>'PRC', //系统时区
        'charset'=>'utf-8', //系统编码
        'language'=>'zh-cn', //默认语言包
        'session'=>'files', //Session存储方式

        //系统相关目录
        'dir'=>array(
            'pao'=> PAO , //系统根目录
            'web'=> PAO.DIRECTORY_SEPARATOR.'web', //公共资源目录
            'log'=> PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Logs', //日志存放目录
            'cache'=> PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Cache', //缓存存放目录
        )
);
