<?php

namespace Portal\Controller;

use Portal\Controller\Controller;

class Test extends Controller
{

    public function index()
    {
        echo 'test Controller';
    }


    public function reg($par)
    {
        var_dump($par);
    }


    public function haha()
    {
        echo APP;
    }
}