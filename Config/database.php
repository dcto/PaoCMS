<?php
defined('PAO') || die('The PaoCMS Load Error');


return array(
    'default'=>array(
            'driver'=>'mysql',      //数据库类型( MySQL = mysql | SQL Server = sqlsrv | SQLite = sqlite | pgSql = pgsql)
            'host'=>'192.168.9.242',    //连接地址
            'port'=>'3306',         //端口
            'database'=>'paocms',   //数据库名称
            'username'  => 'root',  //帐号
            'password'  => 'root',  //密码
            'prefix' => 'pao_',         //表前缀
            'charset'   => 'utf8',  //数据库编码
            'collation' => 'utf8_unicode_ci'
    ),

    '145'=>array(
        'driver'=>'mysql',      //数据库类型( MySQL = mysql | SQL Server = sqlsrv | SQLite = sqlite | pgSql = pgsql)
        'host'=>'10.1.10.145',    //连接地址
        'port'=>'3366',         //端口
        'database'=>'video',   //数据库名称
        'username'  => 'halin',  //帐号
        'password'  => '123456',  //密码
        'prefix' => '',         //表前缀
        'charset'   => 'utf8',  //数据库编码
        'collation' => 'utf8_unicode_ci'
    ),

);
