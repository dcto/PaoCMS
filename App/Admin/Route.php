<?php
Router::group(['prefix'=>'/', 'namespace'=>'Admin\Controller'], function(){

    Router::get('/', 'Index@index');


    Router::group(['tag'=>'user', 'name'=>'会员', 'icon'=>'user', 'prefix'=>'/user'], function(){
        Router::get('/','User@index')->tag('user')->name('会员列表');
        Router::any('/create','User@create')->tag('user_create')->name('创建会员');
        Router::any('/update','User@update')->tag('user_update')->name('修改会员');
        Router::get('/delete','User@delete')->tag('user_delete')->name('删除会员');
    });



});
