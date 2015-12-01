<?php
defined('PAO') || die('The PaoCMS Load Error');


return array(

    '/' => ['GET','as'=>'index', 'to'=>'Test@index'],

    '/test'=> ['ANY', 'as'=>'test', 'to'=>'Test@index'],
    '/reg/(:str)/(:num)' =>['GET', 'as'=>'reg', 'to'=>'Test@reg']

);