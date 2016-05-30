<?php

namespace Admin\Controller;


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Article extends Controller
{

    public function index()
    {
        $this->assign('title', lang('menu.article'));

        return Response::view('article');
    }


    public function create()
    {

        $article = Input::all();
        return $this->alert();

    }

}