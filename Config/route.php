<?php
defined('PAO') || die('The PaoCMS Load Error');

/**
 * 格式说明
 * /test 路由格式 /test/(参数:any|:num|:str|:all)
 * GET = 请求方式 GET|POST|ANY|ALL
 * as = 路由别名
 * to = 控制器@方法名
 *
 * '/test' => ['GET','as'=>'index', 'to'=>'Test@index'],
 * '/test/(:str)' => ['GET','as'=>'index', 'to'=>'Test@testStr'],
 */

return array(

    '/' => ['GET','as'=>'index', 'to'=>'Index@index'],
    '/captcha' => ['GET','as'=>'captcha', 'to'=>'Index@captcha'],
    '/test'=> ['ANY', 'as'=>'test', 'to'=>'Test@index'],
    '/reg/(:str)/(:num)' =>['GET', 'as'=>'reg', 'to'=>'Test@reg']

);