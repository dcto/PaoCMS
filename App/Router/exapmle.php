<?php
Router::group(['prefix'=>'/', 'namespace'=>'App\Controller\Admin'], function(){

    Router::get('/', ['tag'=>'index','call'=>'Index@index']);
    Router::get('/access/login', ['tag'=>'login', 'call'=>'Index@index']);

    Router::group(['call'=>'App\Controller\Admin\Controller@Access'], function(){


        Router::get('/access/logout', ['tag'=>'logout', 'call'=>'Index@index']);

        Router::group(['tag'=>'article', 'name'=>'menu.article', 'icon'=>'file', 'prefix'=>'/article'], function(){
            Router::get('/', ['tag'=>'article','name'=>'menu.article', 'call'=>'Article@index'])->menu(true);
            Router::any('/create', ['tag'=>'article_create', 'name'=>'menu.article_create', 'call'=>'Article@create'])->menu(true);
            Router::any('/update', ['tag'=>'article_update','name'=>'menu.article_update', 'call'=>'Article@update']);
            Router::any('/delete', ['tag'=>'article_delete','name'=>'menu.article_delete', 'call'=>'Article@delete']);
        });

        Router::group(['tag'=>'trees', 'name'=>'menu.trees', 'icon'=>'th-list', 'prefix'=>'/trees'], function(){
            Router::get('/', ['tag'=>'trees','name'=>'menu.trees', 'call'=>'Trees@index'])->menu(true);
            Router::any('/create', ['tag'=>'trees_create','name'=>'menu.trees_create', 'call'=>'Trees@create'])->menu(true);
            Router::any('/update', ['tag'=>'trees_update','name'=>'menu.trees_update', 'call'=>'Trees@update']);
            Router::any('/delete', ['tag'=>'trees_delete','name'=>'menu.trees_delete', 'call'=>'Trees@delete']);
        });

        Router::group(['tag'=>'user', 'name'=>'menu.user', 'icon'=>'user', 'prefix'=>'/user'], function(){
            Router::get('/', ['tag'=>'user','name'=>'menu.user', 'call'=>'User@index'])->menu(true);
            Router::any('/create', ['tag'=>'user_create','name'=>'menu.user_create', 'call'=>'User@create'])->menu(true);
            Router::any('/update', ['tag'=>'user_update','name'=>'menu.user_update', 'call'=>'User@update']);
            Router::any('/delete', ['tag'=>'user_delete','name'=>'menu.user_delete', 'call'=>'User@delete']);
        });

        Router::group(['tag'=>'group', 'name'=>'menu.group', 'icon'=>'group', 'prefix'=>'/group'], function(){
            Router::get('/', ['tag'=>'group','name'=>'menu.group', 'call'=>'Group@index'])->menu(true);
            Router::any('/create', ['tag'=>'group_create','name'=>'menu.group_create', 'call'=>'Group@create'])->menu(true);
            Router::any('/update', ['tag'=>'group_update','name'=>'menu.group_update', 'call'=>'Group@update']);
            Router::any('/delete', ['tag'=>'group_delete','name'=>'menu.group_delete', 'call'=>'Group@delete']);
        });
    });
});
