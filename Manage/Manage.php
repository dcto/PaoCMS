<?php
//����Ӧ������
define('APP',  basename(__FILE__, '.php'));

// Autoload �Զ�����
require('../vendor/autoload.php');

$Portal = new \PAO\Frameworks();

$Portal->Issue();