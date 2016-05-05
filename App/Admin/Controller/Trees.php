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


        if(Request::isMethod('POST')){
            //$Trees = \App\Model\Trees::get(array('id','type','name','level'));
            //$Trees = $Trees ? $Trees->toArray() : array();
           return Response::Json(['nodes'=>[]]);
        }

        $Trees = \App\Model\Trees::where('pid','0')->get();
        return Response::view('trees', ['trees'=>$Trees]);
    }


    public function create()
    {
        if(\App\Model\Trees::where('tag',Request::get('tag'))->exists()){
            return $this->alert(0, lang('alert.exist', lang('menu.trees')));
        }
        $create = \App\Model\Trees::create(Request::all());
        if(!$create) return Response::make(lang('alert.create_failure', lang('module.trees')), 500);
        return Response::Json($create);
    }


    public function update()
    {
        if(!Request::isMethod('POST')){
            $trees = \App\Model\Trees::whereId(Request::get('id'))->get();
            return Response::Json($trees ? $trees->toArray() : []);
        }
        die('sdd');
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