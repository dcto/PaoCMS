<?php

namespace Portal\Controller;


use PAO\Http\Response;


class Test extends Controller
{

    public function index()
    {


        echo 'ddd';

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