<?php
Router::group(['prefix'=>'/','namespace'=>'Admin\Controller'], function(){

    Router::get('/', 'Index@index');

});
