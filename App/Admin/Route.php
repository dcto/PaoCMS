<?php
Router::group(['prefix'=>'/','namespace'=>'Admin\Controller'], function(){

    Router::get('/', 'Index@index');


    Router::group(['prefix'=>'/user', 'name'=>'会员'], function(){
        Router::get('/','User@index')->tag('user.create')->name('会员列表');
        Router::get('/create','User@create')->tag('user.create')->name('创建会员');
        Router::get('/update','User@update')->tag('user.update')->name('修改会员');
        Router::get('/delete','User@update')->tag('user.delete')->name('删除会员');
    });



});
