<?php
defined('PAO') || die('The PaoCMS Load Error');


$cache['file']['dir'] = '/Runtime/Cache';

$cache['redis']['default']['host'] = '127.0.0.1 ';
$cache['redis']['default']['port'] = '6379';
$cache['redis']['default']['timeout'] = 5;
$cache['redis']['default']['database'] = 0;
$cache['redis']['default']['persistent'] = true;
$cache['redis']['default']['options'][Redis::OPT_PREFIX] = 'pao:';


$cache['memcache']['default']['host']       =   '127.0.0.1';
$cache['memcache']['default']['port']       =   '1211';
$cache['memcache']['default']['database']   =   0;
$cache['memcache']['default']['prefix']     =   '';