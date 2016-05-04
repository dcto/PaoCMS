<?php
//定义应用名称
define('APP', __DIR__);

//Autoload 自动载入
$loader = require('../vendor/autoload.php');

//注册自动加载应用路径
$loader->addPsr4(basename(APP).'\\', APP);

//创建实例构建应用
$app = new \PAO\Frameworks();

//发布应用
$app->Issue();