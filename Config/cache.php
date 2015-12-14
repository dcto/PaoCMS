<?php
defined('PAO') || die('The PaoCMS Load Error');

return array(

    'redis' => array(
        'default'=>array(
            'host' => '127.0.0.1',
            'port' => '6379',
            'timeout' => 5,
            'database' => 0,
            'persistent' => true,
            'options' =>[
                Redis::OPT_PREFIX => 'pao:',
                        ]

        ),
        'test'=>array(
            'host' => '10.1.10.145',
            'port' => '6379',
            'prefix' => 'pao:',
            'timeout' => 5,
            'database' => 0,
            'persistent' => false,
            'options' => []
        )

	),


	'memcached' => array(
		'host'=>'127.0.0.1',
		'port'=>'1211',
        'database' => 0,
        'prefix' => ''
	)
);
