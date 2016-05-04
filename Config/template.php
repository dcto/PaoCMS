<?php
defined('PAO') || die('The PaoCMS Load Error');


return array(
    'dir' => APP.DIRECTORY_SEPARATOR.'View', //视图路径
    'cache' => false,//PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Cache', //械板缓存
    'suffix' => '.html', //视图文件后缀

    //模板全局变量设定
    'variable' => array(
            'time'=>time(),
    )
);
