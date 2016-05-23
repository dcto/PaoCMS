<?php


namespace Admin\Controller;


use Lang;
use Request;
use Response;
use PAO\Exception\NotFoundHttpException;

class Group extends Controller
{


    public function index()
    {
        $this->assign('title', lang('menu.group'));
        $var['group'] = \App\Model\Group::all();
        return Response::view('group', $var);
    }


    public function create()
    {
        if(!Request::isMethod('post')) return $this->index();

        $group['name'] = Request::get('name');
        $group['nickname'] = Request::get('nickname');
        $group['status'] = Request::get('status');

        if(!$this->checkForm($group)) return $this->alert();

        $group['permission'] = Request::not(array('id','name','nickname','status'));

        if(\App\Model\Group::create($group)) {
            $this->status = true;
            $this->message = sprintf(lang('alert.create_success'), lang('menu.group'));
        }else{
            $this->status = false;
            $this->message = sprintf(lang('alert.create_failure'), lang('menu.group'));
        }
        return $this->alert();
    }


    public function update()
    {
        if(Request::isMethod('post')){
            $ids =  Request::get('id');
            if(!$ids) return $this->alert(false, lang('alert.id-empty'));

            $group['name'] = Request::get('name');
            $group['nickname'] = Request::get('nickname');
            $group['status'] = Request::get('status');
            if(!$this->checkForm($group)){ return $this->alert();}
            $group['permission'] = Request::not(array('id','name','nickname','status'));

            if(\App\Model\Group::find($ids)->update($group)){
                $this->status = true;
                $this->message = sprintf(lang('alert.update_success'), lang('menu.group'));
            }else{
                $this->status = false;
                $this->message = sprintf(lang('alert.update_failure'), lang('menu.group'));
            }
            return $this->alert();
        }

        if(!$id = Request::get('id')) throw new NotFoundHttpException;

        $group = \App\Model\Group::find($id)->toArray();
        $group = array_merge($group, $group['permission']);
        unset($group['permission']);
        return Response::Json($group);
    }

    /**
     * [delete删除]
     * @return mixed
     */
    public function delete()
    {
        $ids = (array) Request::get('id');
        $delete = \App\Model\Group::whereIn('id', $ids)->delete();
        if($delete){
            $this->status = true;
            $this->message = lang('alert.delete_success', implode(',', $ids));
        }else{
            $this->status = false;
            $this->message = lang('alert.delete_failure', implode(',', $ids));
        }
        return $this->alert();
    }
}