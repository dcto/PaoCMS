<?php

namespace Manage\Controller;



use Manage\Model\Member;
use PAO\Http\Response;
use Manage\Model\Admin;
use Manage\Model\AdminGroup;
use Session;
use Cookie;

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

        Session::set('11','2l2l22222222222');

        Cookie::set('ddd',time());


        print_r(Cookie::all());

        return new Response();
    }

}