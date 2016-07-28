<?php
Router::group(['prefix'=>'/', 'namespace'=>'Admin\Controller'], function(){

    Router::get('/', 'Index@index');
    Router::get('/access/login', ['tag'=>'login', 'call'=>'Index@index']);
    Router::get('/access/logout', ['tag'=>'logout', 'call'=>'Index@index']);

    Router::group(['tag'=>'article', 'name'=>'menu.article', 'icon'=>'file', 'prefix'=>'/article'], function(){
        Router::get('/','Article@index')->tag('article')->name('menu.article')->menu('true');;
        Router::any('/create','Article@create')->tag('article_create')->name('menu.article_create')->menu(true);
        Router::any('/update','Article@update')->tag('article_update')->name('menu.article_update');
        Router::any('/delete','Article@delete')->tag('article_delete')->name('menu.article_delete');
    });

    Router::group(['tag'=>'trees', 'name'=>'menu.trees', 'icon'=>'th-list', 'prefix'=>'/trees'], function(){
        Router::get('/','Group@index')->tag('trees')->name('文章表');
        Router::any('/create','Trees@create')->tag('trees_create')->name('menu.trees_create')->menu(true);
        Router::any('/update','Trees@update')->tag('trees_update')->name('menu.trees_update');
        Router::any('/delete','Trees@delete')->tag('trees_delete')->name('menu.trees_delete');
    });

    Router::group(['tag'=>'user', 'name'=>'menu.user', 'icon'=>'user', 'prefix'=>'/user'], function(){
        Router::get('/','User@index')->tag('user')->name('menu.user')->menu('true');
        Router::any('/create','User@create')->tag('user_create')->name('menu.user_create')->menu(true);
        Router::any('/update','User@update')->tag('user_update')->name('menu.user_update');
        Router::any('/delete','User@delete')->tag('user_delete')->name('menu.user_delete');
    });

    Router::group(['tag'=>'group', 'name'=>'menu.group', 'icon'=>'group', 'prefix'=>'/group'], function(){
        Router::get('/','Group@index')->tag('group')->name('menu.group')->menu('true');;
        Router::any('/create','Group@create')->tag('group_create')->name('menu.group_create')->menu(true);
        Router::any('/update','Group@update')->tag('group_update')->name('menu.group_update');
        Router::any('/delete','Group@delete')->tag('group_delete')->name('menu.group_delete');
    });
});
