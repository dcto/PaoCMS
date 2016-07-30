<?php
defined('PAO') || die('The PaoCMS Load Error');


$database['default']['driver']      =   'mysql';        //数据库类型( MySQL = mysql | SQL Server = sqlsrv | SQLite = sqlite | pgSql = pgsql)
$database['default']['host']        =   'localhost';    //连接地址
$database['default']['port']        =   '3306';         //端口
$database['default']['database']    =   'paocms';       //数据库名称
$database['default']['username']    =   'root';         //帐号
$database['default']['password']    =   'root';         //密码
$database['default']['prefix']      =   'pao_';         //表前缀
$database['default']['charset']     =   'utf8';         //数据库编码
$database['default']['collation']   =   'utf8_unicode_ci';
