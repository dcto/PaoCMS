<?php
//定义根目录
defined('PAO') || define('PAO', dirname(__DIR__));

//定义APP路径
defined('DIR') || define('DIR', __DIR__);

//定义应用名称
defined('APP') || define('APP', trim(basename($_SERVER['SCRIPT_NAME']),'.php'));

//Autoload 自动载入
require('../vendor/autoload.php');

//创建实例构建应用
$app = new PAO\Application();

//发布应用
$app->Issue();