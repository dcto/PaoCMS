<?php

namespace Manage\Controller;




use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\Response;
use Manage\Model\Category;
use Manage\Model\Member;
use Manage\Model\Admin;
use Manage\Model\AdminGroup;
use Manage\Model\Message;
use Manage\Model\Setting;
use PAO\Cache\FileSystem;


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


            $key =  'test';

           //$s = \Cache::file('test')->set($key,array('a'=>0,'b'=>3));


            //Cache::file()->set($key, array('ab'=>'test','dddd'=>12345689999));

//        Cache::file('abc')->del($key);

echo '<hr />';
        var_dump(Cache::file()->get($key));
            //print_r($cache->status());

          // \Cache::file('test')->del($key);
            //var_dump(\Cache::file('test')->has($key));

        Message::down();
        Message::up();

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

        $this->assign('class', '');

        return Response::view('index');
    }

}