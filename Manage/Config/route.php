<?php
defined('PAO') || die('The PaoCMS Load Error');

return array(

    '/' => ['GET','as'=>'index', 'to'=>'Index@index'],
    '/auth/login' =>[['GET','POST'], 'as'=>'login', 'to'=>'auth@login'],
    '/auth/logout' =>['GET', 'as'=>'logout', 'to'=>'auth@logout'],

    '/user/publish' =>['GET', 'as'=>'user_publish', 'to'=>'user@publish'],
    '/user/modify/(:num)' =>['GET', 'as'=>'user_modify', 'to'=>'user@modify'],
    '/user/delete/(:num)' =>['GET', 'as'=>'user_delete', 'to'=>'user@delete'],
    '/user/password/(:num)' =>['GET', 'as'=>'user_password', 'to'=>'user@password']
);
