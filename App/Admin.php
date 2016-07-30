<?php
//定义应用名称
define('APP', __DIR__);

define('NAME', trim(basename(__FILE__), '.php'));

//Autoload 自动载入
$loader = require('../vendor/autoload.php');

//创建实例构建应用
$app = new \PAO\Frameworks();

//发布应用
$app->Issue();