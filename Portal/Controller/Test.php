<?php

namespace Portal\Controller;


use PAO\Http\Response;


class Test extends Controller
{

    public function index()
    {

        //echo 2/0;

        $this->assign('test', '测试');
        return $this->view('index');
    }


    public function regs($par)
    {
        var_dump($par);
    }


}