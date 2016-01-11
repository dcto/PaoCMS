<?php
defined('PAO') || die('The PaoCMS Load Error');

return array(

        'debug' => true, //debugģʽ

        'log' => true, //�Ƿ�������־�ܿ���

        'token'=> 'PaoSystem', //��վ��ʶ

        'timezone'=>'PRC', //ϵͳʱ��
        'charset'=>'utf-8', //ϵͳ����

        'session'=>'files', //Session�洢��ʽ

        '404'=>'404.html',
        '500'=>'500.html',

        //ϵͳ���Ŀ¼
        'dir'=>array(
            'pao'=> PAO , //ϵͳ��Ŀ¼
            'web'=> PAO.DIRECTORY_SEPARATOR.'web', //������ԴĿ¼
            'log'=> PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Logs', //��־���Ŀ¼
            'cache'=> PAO.DIRECTORY_SEPARATOR.'RunTime'.DIRECTORY_SEPARATOR.'Cache', //������Ŀ¼
        ),




);
