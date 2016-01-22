<?php

namespace Portal\Controller;





class Test extends Controller
{

    public function index()
    {

       // $redis = $this->container->make('redis');
        //$redis->set('aa','kdk');
        //print_r($redis->info());
        //print_r($redis = $this->container->make('redis')->connection('145')->info());

       //$this->container->make('db');

       // $d = DB::insert("insert into test (test) VALUE  (?)", [ uniqid()]);

        //$result = DB::select("select * from test");
        //$result = \Portal\Model\Test::paginate(5);

        //\Manage\Model\Admin::down();
        //\Manage\Model\Admin::up();

        //print_r(DB::getQueryLog());


       // $db = $this->container->DI('db');




        //$test = \Portal\Model\Test::all();



        //$admin = \Manage\Model\Admin::find(1)->group;





        //DB::select('select * from test');

        //DB::select('select * from test');


        //$redis = $this->container->make('cache')->redis();

      print_r(get_included_files());
      //$this->container->make('test');
        $this->assign('test', '');
       // $this->assign('data',$result);
        return $this->view('index');
        //\Response::view('index')->send();
    }


    public function regs($par)
    {
        var_dump($par);
    }


}
