<?php

namespace Portal\Controller;


use PAO\Http\Response;


class Test extends Controller
{

    public function index()
    {
        $this->assign('test', '测试');
        return $this->view('index');
    }


    public function reg($par)
    {
        var_dump($par);
    }


}