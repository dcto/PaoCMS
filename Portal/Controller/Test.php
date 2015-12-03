<?php

namespace Portal\Controller;


use PAO\Http\Response;


class Test extends Controller
{

    public function index()
    {


        return true;

    }


    public function reg($par)
    {
        var_dump($par);
    }


    public function haha()
    {
        $this->checkLogin();
        return new Response();
    }
}