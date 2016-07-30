<?php
defined('PAO') || die('The PaoCMS Load Error');

$upload['path'] = './'; //保存文件目录
$upload['size'] = 1024; //上传大小限制
$upload['kind'] = ['gif','png','jpg','jpeg','swf']; //允许上传文件