<?php
//定义应用名称
define('APP',  basename(__FILE__, '.php'));

// Autoload 自动载入
require('../vendor/autoload.php');

// 路由配置
require '../Config/route.php';


$nexus = new \Nexus\Nexus();

$nexus->wizard();