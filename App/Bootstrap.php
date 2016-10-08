<?php
//定义根目录
defined('PAO') || define('PAO', dirname(__DIR__));

//定义APP路径
defined('APP') || define('APP', __DIR__);

//定义应用名称
defined('NAME') || define('NAME', trim(basename($_SERVER['SCRIPT_NAME']),'.php'));

//定义运行目录
defined('RUNTIME') || define('RUNTIME', dirname(__DIR__).'/RunTime');

//Autoload 自动载入
$loader = require('../vendor/autoload.php');

//创建实例构建应用
$app = new \PAO\Application($loader);

//发布应用
$app->Issue();