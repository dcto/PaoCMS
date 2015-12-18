<?php

namespace Manage\Controller;




use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\Response;
use Manage\Model\Category;
use Manage\Model\Member;
use Manage\Model\Admin;
use Manage\Model\AdminGroup;
use Manage\Model\Setting;


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


        /**
         * @var Illuminate\Database\Eloquent\Model $config
         */

        //Config::create(['key' => uniqid(),'value'=>uniqid()]);
        //Config::create(['key' => uniqid(),'value'=>uniqid()]);

        //print_r((new Admin)->getTable());
        //print_r(Admin::table());

      //  echo class_basename($this); //str_replace('\\', '', Str::snake(Str::plural(class_basename($this))));

        $d = \Config::get('database');
        print_r($d);
/**
        Admin::down();
        Admin::up();
        AdminGroup::down();
        AdminGroup::up();
        Setting::down();
        Setting::up();
        Member::down();
        Member::up();
        Category::down();
        Category::up();
 * */
        //$redis = \Redis::info();

        //print_r($redis);


        //('response')->show('ddd');

        //$this->container->make('response')->show('dwww');

        $this->assign('class', __CLASS__);

        return Response::view('index');
    }

}