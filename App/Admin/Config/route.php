<?php
defined('PAO') || die('The PaoCMS Load Error');

/**
 * 路由表文件,请严格按照格式配置
 */

return array(
    '/'=>array(
        '/' => ['GET','as'=>'/', 'to'=>'Index@index'],
        '/auth/login' =>[['GET','POST'], 'as'=>'login', 'to'=>'auth@login'],
        '/auth/logout' =>['GET', 'as'=>'logout', 'to'=>'auth@logout'],
        ),

    'user'=>array(
        'icon'=>'user',
        'menu'=>true,
        'name'=>'menu.user',

        'route'=>array(
            '/user' => ['GET', 'as'=>'user', 'to'=>'user@index', 'name'=>'menu.user', 'menu'=>true],
            '/user/create' => ['ANY', 'as'=>'user-create', 'to'=>'user@create', 'name'=>'menu.user_create',],
            '/user/update' => ['ANY', 'as'=>'user-update', 'to'=>'user@update', 'name'=>'menu.user_update'],
            '/user/delete' => ['ANY', 'as'=>'user-delete', 'to'=>'user@delete', 'name'=>'menu.user_delete'],
/*
                '/group' => ['GET', 'as'=>'group', 'to'=>'Group@index','name'=>'menu.group', 'menu'=>true],
                '/group/create' => ['ANY', 'as'=>'group-create', 'to'=>'Group@create','name'=>'menu.group_create',],
                '/group/update' => ['ANY', 'as'=>'group-update', 'to'=>'Group@update','name'=>'menu.group_update'],
                '/group/delete' => ['ANY', 'as'=>'group-delete', 'to'=>'Group@delete','name'=>'menu.group_delete'],
*/
        )
    ),

    'trees'=>array(
        'icon'=>'th-list',
        'menu'=>true,
        'name'=>'menu.trees',

        'route'=>array(
            '/trees'=>['ANY', 'as'=>'trees', 'to'=>'trees@index','name'=>'menu.trees', 'menu'=>true],
            '/trees/create'=>['POST', 'as'=>'trees-create', 'to'=>'trees@create','name'=>'menu.trees_create'],
            '/trees/update'=>['ANY', 'as'=>'trees-update', 'to'=>'trees@update','name'=>'menu.trees_update'],
            '/trees/delete'=>['ANY', 'as'=>'trees-delete', 'to'=>'trees@delete','name'=>'menu.trees_delete'],
        )
    ),
    /*
    'admin'=>array(
        'icon'=>'lock',
        'menu'=>true,
        'name'=>'menu.admin',

        'route'=>array(
            //'/admin/(:all)' => ['', 'as'=>'admin-create', 'to'=>'admin@='],
            '/admin' => ['GET', 'as'=>'admin', 'to'=>'admin@index', 'name'=>'menu.admin', 'menu'=>true],
            '/admin/create' => ['ANY', 'as'=>'admin-create', 'to'=>'admin@create', 'name'=>'menu.admin_create',],
            '/admin/update' => ['ANY', 'as'=>'admin-update', 'to'=>'admin@update', 'name'=>'menu.admin_update'],
            '/admin/delete' => ['ANY', 'as'=>'admin-delete', 'to'=>'admin@delete', 'name'=>'menu.admin_delete'],
            '/admin/password' => ['ANY', 'as'=>'admin-password', 'to'=>'admin@password', 'name'=>'menu.admin_password', 'menu'=>true],
        )
    ),
    */
    'Group'=>array(
        'icon'=>'group',
        'menu'=>true,
        'name'=>'menu.group',

        'route'=>array(
            '/group' => ['GET', 'as'=>'group', 'to'=>'Group@index','name'=>'menu.group', 'menu'=>true],
            '/group/create' => ['ANY', 'as'=>'group-create', 'to'=>'Group@create','name'=>'menu.group_create',],
            '/group/update' => ['ANY', 'as'=>'group-update', 'to'=>'Group@update','name'=>'menu.group_update'],
            '/group/delete' => ['ANY', 'as'=>'group-delete', 'to'=>'Group@delete','name'=>'menu.group_delete'],
        )
    ),


    'Article'=>array(
        'icon'=>'',
        'menu'=>true,
        'name'=>'menu.article',

        'route'=>array(
            '/article'=>['GET', 'as'=>'article', 'to'=>'Article@index', 'name'=>'menu.article', 'menu'=>true],
            '/article/create'=>['ANY', 'as'=>'article-create', 'to'=>'Article@create', 'name'=>'menu.article_create'],
            '/article/update'=>['ANY', 'as'=>'article-update', 'to'=>'Article@update', 'name'=>'menu.article_update'],
            '/article/delete'=>['ANY', 'as'=>'article-delete', 'to'=>'Article@delete', 'name'=>'menu.article_delete'],

        )
    ),

);
