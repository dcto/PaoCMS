<?php

namespace Manage\Controller;

use PAO\Http\Response;

class Controller extends \PAO\Controller
{

    public function checkLogin()
    {
        return new Response('未登陆');
    }

}

