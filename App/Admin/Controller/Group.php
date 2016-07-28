<?php


namespace Admin\Controller;

use PAO\Exception\NotFoundHttpException;
use PAO\Http\Request;
use PAO\Http\Response;

class Group extends Controller
{


    public function index()
    {
        $this->assign('title', lang('menu.group'));
        $var['group'] = \App\Model\Group::all();
        return view('group', $var);
    }


    public function create(Request $request, Response $response)
    {
        if(!$request->isMethod('post')) return $response->url(url('@group').'#create');

        $group['name'] = $request->get('name');
        $group['nickname'] = $request->get('nickname');
        $group['status'] = $request->get('status');

        if(!$this->checkForm($group)) return $this->alert();

        $group['permission'] = $request->not(array('id','name','nickname','status'));

        if(\App\Model\Group::create($group)) {
            $this->status = true;
            $this->message = sprintf(lang('alert.create_success'), lang('menu.group'));
        }else{
            $this->status = false;
            $this->message = sprintf(lang('alert.create_failure'), lang('menu.group'));
        }
        return $this->alert();
    }


    public function update(Request $request)
    {
        if($request->isMethod('post')){
            $ids =  $request->get('id');
            if(!$ids) return $this->alert(false, lang('alert.id-empty'));

            $group['name'] = $request->get('name');
            $group['nickname'] = $request->get('nickname');
            $group['status'] = $request->get('status');
            if(!$this->checkForm($group)){ return $this->alert();}
            $group['permission'] = $request->not(array('id','name','nickname','status'));

            if(\App\Model\Group::find($ids)->update($group)){
                $this->status = true;
                $this->message = sprintf(lang('alert.update_success'), lang('menu.group'));
            }else{
                $this->status = false;
                $this->message = sprintf(lang('alert.update_failure'), lang('menu.group'));
            }
            return $this->alert();
        }

        if(!$id = $request->get('id')) throw new NotFoundHttpException;

        $group = \App\Model\Group::find($id)->toArray();
        $group = array_merge($group, $group['permission']);
        unset($group['permission']);
        return \Response::Json($group);
    }

    /**
     * [delete删除]
     * @return mixed
     */
    public function delete(Request $request)
    {
        $ids = (array) $request->get('id');
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