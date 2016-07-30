<?php

namespace Admin\Controller;


use PAO\Http\Request;
use PAO\Http\Response;

class Article extends Controller
{

    public function index()
    {
        $this->assign('title', lang('menu.article'));

        return view('article');
    }


    public function create(Request $request, Response $response)
    {
        if(!$request->isMethod('post')) return $response->url(url('@article').'#create');

    }

}