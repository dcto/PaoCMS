<?php
defined('PAO') || die('The PaoCMS Load Error');

define('DS', DIRECTORY_SEPARATOR);


return array(

        'debug'=>true, //debugģʽ

        'log'=>true, //�Ƿ�������־�ܿ���

        '404'=>'404.html',
        '500'=>'500.html',

        //ϵͳ���Ŀ¼
        'dir'=>array(
            'pao'=> dirname(__DIR__) , //ϵͳ��Ŀ¼
            'web'=> dirname(__DIR__).DS.'web', //������ԴĿ¼
            'logs'=> dirname(__DIR__).DS.'RunTime'.DS.'logs', //��־���Ŀ¼
            'cache'=> dirname(__DIR__).DS.'RunTime'.DS.'Cache', //������Ŀ¼
            'controller'=>'Controller',
            'model'=>'Model',
            'view'=>'View'
        ),

        //ϵͳ�������
        'system'=>array(
            'timezone'=>'PRC', //ϵͳʱ��
            'charset'=>'utf-8', //ϵͳ����
        ),


);