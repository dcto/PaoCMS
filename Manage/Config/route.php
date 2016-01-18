<?php
defined('PAO') || die('The PaoCMS Load Error');

return array(

    '/' => ['GET','as'=>'index', 'to'=>'Index@index'],
    '/login' =>['GET', 'as'=>'login', 'to'=>'Login@index']

);
