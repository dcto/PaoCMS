<?php
defined('PAO') || die('The PaoCMS Load Error');


return array(
   'a'=>array(
       'b'=>array(
           'c'=>array(
    'type'=>'mysql',
	'host'=>'localhost', //连接地址
	'port'=>'3306',		 //端口
	'name'=>'paocms',	 //数据库名称
    'username'  => 'root',
    'password'  => 'password',
	'prefix'=>'',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci'
    )))
);
