<?php

namespace Portal\Controller;




use PAO\Http\Response;
use PAO;
use DB;

class Test extends Controller
{

    public function index()
    {

        $redis = $this->container->make('redis');
        $redis->set('aa','kdk');
        //print_r($redis->info());
echo '<hr/>';
        //print_r($redis = $this->container->make('redis')->connection('145')->info());

       $this->container->make('db');

       // $d = DB::insert("insert into test (test) VALUE  (?)", [ uniqid()]);

        //$result = DB::select("select * from test");
        $result = \Portal\Model\Test::paginate(5);



        //print_r(DB::getQueryLog());


       // $db = $this->container->DI('db');




        //$test = \Portal\Model\Test::all();





//DB::select('select * from test');




        $this->assign('test', '测试');
        $this->assign('data', $result);
        return $this->view('index');
    }


    public function regs($par)
    {
        var_dump($par);
    }


}