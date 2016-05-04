<?php
/**
 * Created by PhpStorm.
 * User: DC
 * Date: 4/2/16
 * Time: 2:51 PM
 */

namespace Admin\Controller;

use DB;
use Lang;
use Request;
use Response;

class Trees extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->assign('title', lang('menu.trees'));
    }


    public function index()
    {
    /*
        \App\Model\Trees::down();
        \App\Model\Trees::up();
    */
        if(Request::isMethod('POST')){
            $Trees = \App\Model\Trees::get(array('id','type','name','level'));
            $Trees = $Trees ? $Trees->toArray() : array();
            return Response::Json(['nodes'=>$Trees]);
        }

        $Trees = \App\Model\Trees::whereRoot(1)->get();
        return Response::view('trees', ['trees'=>$Trees]);
    }


    public function create()
    {
        $create = \App\Model\Trees::create(Request::all());
        if(!$create) return Response::make(lang('alert.create_failure', lang('module.trees')), 500);
        return Response::Json($create);
    }


    public function update()
    {
        $update = \App\Model\Trees::whereId(Request::get('id'))->update(Request::all());
        if(!$update) return Response::make(lang('alert.update_failure', lang('module.trees')), 500);
        return Response::Json(Request::all());
    }

    public function delete()
    {
        $delete = \App\Model\Trees::whereId(Request::get('id'))->delete();
        if(!$delete) return Response::make(lang('alert.delete_failure', lang('module.trees')), 500);
        return Response::make(1);
    }
}