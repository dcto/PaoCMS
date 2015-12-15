<?php

namespace Manage\Controller;




use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Manage\Model\Member;
use Manage\Model\Admin;
use Manage\Model\AdminGroup;


class Index extends Controller
{


    public function index()
    {

/*        Admin::down();
        Admin::up();

        AdminGroup::down();
        AdminGroup::up();

        Member::down();
        Member::up();*/

       // Session::set('11','2l2l22222222222');

        Cookie::set('ddd',date('Y-m-d H:i:s'));

        Cookie::del('ddd');
//        print_r(Cookie::all());

        //$redis = $this->container->make('redis');


        //$d= $this->container->make('config')->get('cache');

        $d = \Config::get('cache');

        var_dump($d);
        //$redis = \Redis::info();

        //print_r($redis);


        //('response')->show('ddd');

        //$this->container->make('response')->show('dwww');

        Response::show('<hr />show');
    }

}