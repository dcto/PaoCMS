<?php
defined('PAO') || die('The PaoCMS Load Error');


return array(
    'dir' => PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'View', //ģ��·��
    'cache' => PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Cache', //ģ�建��
    'suffix' => '.html', //ģ���׺

    //ģ������Զ���
    'variable' => array(
            'time'=>time(),
    )
);
