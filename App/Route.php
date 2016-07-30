<?php

Router::group(['tag'=>'a', 'name'=>'a', 'icon'=>'a', 'namespace'=>'App\Controller'], function () {
    Router::get('/', ['tag'=>'a','call'=>'Index@index'])->tag('test');
    //Router::any('/(:num)', 'Index@Index')

});
/*
Router::group(['lang'=>'zh-cn', 'namespace'=>'Index\Controller'], function() {

    Router::group(['tag'=>'b', 'name'=>'b', 'lang'=>'en-US', 'icon'=>'b'], function () {
        //Router::any('/(:num)', 'Index@Index');
        Router::any('/(:uuid)', ['tag' => 'index', 'call' => 'Index@index', 'lang' => 'en-US', 'name' => '哈哈']);

        // Demo Routes
        Router::get('/demo', 'IndexIndex@in')->name('abc');
        Router::get('/demo/2', 'IndexIndex@in')->lang('zh-TW');

    });

    Router::group(['tag'=>'c', 'name'=>'c', 'icon'=>'c'], function () {

        Router::group(['tag'=>'d', 'name'=>'d'], function(){
            Router::get('/aaa', 'Index@index');
        });

        //Router::any('/(:num)', 'Index@Index')

    });

     //   Router::restful('/teest', 'Index');
});

*/


//print_r(\Request::url('$controller'));