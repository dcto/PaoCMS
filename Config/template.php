<?php
defined('PAO') || die('The PaoCMS Load Error');


return array(
    'dir' => PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'View', //模板路径
    'cache' => PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Cache', //模板缓存
    'suffix' => '.html', //模板后缀

    //模版变量自定义
    'variable' => array(
            'time'=>time(),
    )
);
