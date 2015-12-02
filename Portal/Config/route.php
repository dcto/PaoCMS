<?php

return array(
    '/' => ['GET','as'=>'index', 'to'=>'Test@haha'],
    '/test' =>['GET', 'as'=>'test', 'to'=> function(){
        echo 'DDWWWWWWWWWWW';
    }]
);