<?php
defined('PAO') || die('The PaoCMS Load Error');

return array(

        'debug'=>true, //debugģʽ

        'log'=>true, //�Ƿ�������־�ܿ���

        '404'=>'404.html',
        '500'=>'500.html',

        //ϵͳ���Ŀ¼
        'dir'=>array(
            'pao'=> PAO , //ϵͳ��Ŀ¼
            'web'=> PAO.DIRECTORY_SEPARATOR.'web', //������ԴĿ¼
            'logs'=> PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Logs', //��־���Ŀ¼
            'cache'=> PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Cache', //������Ŀ¼
        ),

        //ϵͳ�������
        'system'=>array(
            'timezone'=>'PRC', //ϵͳʱ��
            'charset'=>'utf-8', //ϵͳ����
        ),


);