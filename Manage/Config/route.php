<?php
defined('PAO') || die('The PaoCMS Load Error');

return array(

    '/' => ['GET','as'=>'index', 'to'=>'Index@index'],
    '/auth/login' =>[['GET','POST'], 'as'=>'login', 'to'=>'auth@login'],
    '/auth/logout' =>['GET', 'as'=>'logout', 'to'=>'auth@logout'],
    '/admin' => ['GET', 'as'=>'admin', 'to'=>'admin@index'],
    '/admin/(:all)' => ['GET', 'as'=>'admin-create', 'to'=>'admin@='],

    '/user/insert' =>['GET', 'as'=>'user_publish', 'to'=>'user@insert'],
    '/user/update/(:num)' =>['GET', 'as'=>'user_modify', 'to'=>'user@update'],
    '/user/delete/(:num)' =>['GET', 'as'=>'user_delete', 'to'=>'user@delete'],
    '/user/password/(:num)' =>['GET', 'as'=>'user_password', 'to'=>'user@password']
);
