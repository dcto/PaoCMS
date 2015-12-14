<?php
//定义应用名称
define('APP',  basename(__FILE__, '.php'));

// Autoload 自动载入
require('../vendor/autoload.php');

$Portal = new \PAO\Frameworks();

$Portal->Issue();