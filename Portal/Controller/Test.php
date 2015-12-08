<?php

namespace Portal\Controller;


use PAO\Http\Response;


class Test extends Controller
{

    public function index()
    {
        $log = $this->container->DI('log');

        $log->info(time());


        $this->assign('test', '测试');
        return $this->view('index');
    }


    public function regs($par)
    {
        var_dump($par);
    }


}