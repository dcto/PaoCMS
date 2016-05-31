<?php
defined('PAO') || die('The PaoCMS Load Error');

/**
 * ��ʽ˵��
 * /test ·�ɸ�ʽ /test/(����:any|:num|:str|:all)
 * GET = ����ʽ GET|POST|ANY|ALL
 * as = ·�ɱ���
 * to = ������@������
 *
 * '/test' => ['GET','as'=>'index', 'to'=>'Test@index'],
 * '/test/(:str)' => ['GET','as'=>'index', 'to'=>'Test@testStr'],
 */

return array(
    '/'=>array(
        '/' => ['ANY','as'=>'index', 'to'=>'Index@index'],
        '/captcha' => ['GET','as'=>'captcha', 'to'=>'Index@captcha'],
        '/test'=> ['ANY', 'as'=>'test', 'to'=>'Test@index'],
        '/reg/(:str)/(:num)' =>['GET', 'as'=>'reg', 'to'=>'Test@reg']
    )
);