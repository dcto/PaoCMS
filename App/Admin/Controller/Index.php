<?php

namespace Admin\Controller;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;


class Index extends Controller
{

    public function captcha()
    {
        return $this->container->make('captcha')->make();
    }

    public function index()
    {
        return \Response::view('index');
    }

    public function test()
    {

        print_r(Request::path());
        echo '<br />';

            echo '<pre>';
            print_r(Config::get('config'));
            echo '<hr/>';
/*        Admin::down();
        Admin::up();

        AdminGroup::down();
        AdminGroup::up();

        Member::down();
        Member::up();*/

        Session::set('11','2l2l22222222222');

            print_r(Session::get('11'));
            echo '<br />';
        $this->container->make('cookie')->set('as',date('Y-m-d H:i:s'));

      //  \Cookie::del('ddd');
            $d = $this->container->make('cookie')->has('as');
            var_dump($d);
        print_r($this->container->make('cookie')->get('as'));
            echo '<Br />';
        print_r($this->container->make('request')->getRealMethod());
        echo '<br />';

        print_r($this->container->make('request')->method());

//        print_r($this->container->make('request'));
        //$redis = $this->container->make('redis');


        print_r(Session::all());


        echo '<hr/>';
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

          // $s = Cache::file('test')->set($key,array('a'=>0,'b'=>3));


            //Cache::file()->set($key, array('ab'=>'test','dddd'=>12345689999));

//        Cache::file('abc')->del($key);


        //var_dump(Cache::file()->get($key));
            //print_r($cache->status());

          // \Cache::file('test')->del($key);
            //var_dump(\Cache::file('test')->has($key));

        //Message::down();
       // Message::up();

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

           // Log::to('test')->info(__METHOD__.'='.date('Y-m-d H:i:s'));
        echo '<hr />';

        echo $this->container->make('translator')->get('title');

        echo $this->container->make('translator')->get('user.register');

        echo '<hr />';
        echo $this->container->make('route')->get('reg',array('abcd','erere'));
        echo '<hr />';


        $this->assign('class', __METHOD__);
          return $this->container->make('response')->view('index');
        //return Response::view('index');
    }

}
