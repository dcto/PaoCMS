<?php

namespace Portal\Controller;




use PAO\Http\Response;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;

use Illuminate\Support\Facades\Event;

class Test extends Controller
{

    public function index()
    {
       $this->container->DI('db');
        DB::connection()->enableQueryLog();
       // $d = DB::insert("insert into test (test) VALUE  (?)", [ uniqid()]);

        $result = DB::select("select * from test");

        print_r($result);

        //DB::connection()->enableQueryLog();

        print_r(DB::getQueryLog());

       // $db = $this->container->DI('db');



        //$test = \Portal\Model\Test::all();


//DB::select('select * from test');




        $this->assign('test', '测试');
        return $this->view('index');
    }


    public function regs($par)
    {
        var_dump($par);
    }


}