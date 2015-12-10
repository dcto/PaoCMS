<?php
defined('PAO') || die('The PaoCMS Load Error');

return array(

    'redis' => array(
		'host' => '127.0.0.1',
		'port' => '6379',
        'database' => 0,
        'prefix' => ''
	),


	'memcached' => array(
		'host'=>'127.0.0.1',
		'port'=>'1211'
	)
);
