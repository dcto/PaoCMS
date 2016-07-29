<?php

namespace Admin\Controller;


class Article extends Controller
{

    public function index()
    {
        $this->assign('title', lang('menu.article'));

        return view('article');
    }


    public function create()
    {

        return $this->alert();

    }

}