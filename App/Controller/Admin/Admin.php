<?php

namespace App\Controller\Admin;

use DB;
use Config;
use Input;
use Request;
use Response;
use Crypt;
use PAO\Exception\NotFoundHttpException;


class Admin extends Controller
{

    /**
     * [index]
     * @return mixed
     */
    public function index()
	{
/**
        \App\Model\Admin::down();
        \App\Model\Admin::up();
*/
        $this->assign('title', lang('menu.admin'));
        if(Input::has('w')){
            $w = trim(Input::get('w'));
            $assign['list'] = \App\Model\Admin::where('username', 'LIKE', "%$w%")
                ->Orwhere('email', 'LIKE', "%$w%")
                ->with('group')->paginate(Config::get('config.page'))->appends(Input::all('!page'));
        }else {
            $assign['list'] = \App\Model\Admin::with('group')->paginate(Config::get('config.page'));
        }
            $assign['group'] = \App\Model\AdminGroup::all();
	 	return Response::view('admin', $assign);
	}

    /**
     * [create创建]
     * @return mixed
     */
	public function create()
	{
        $admin = Input::all();
        if(!$this->checkForm($admin)) return $this->alert();
            $user = \App\Model\Admin::whereUsername(Input::get('username'))->first();
            if(!$user){
                $admin['password'] = md5(Crypt::encrypt(Input::get('password1')));

                $adminId = \App\Model\Admin::create($admin);
                if($adminId){
                    $this->status = true;
                    $this->message = lang('alert.create_success', lang('admin.title'));
                }else{
                   $this->message = lang('alert.create_failure', lang('admin.title'));
                }
            }else{
                $this->message = lang('alert.exist', Input::get('username'));
            }

        return $this->alert();
	}

    /**
     * [update更新]
     * @return mixed
     */
	public function update()
	{

        if(Request::method()=='POST')
        {
            $ids =  Input::get('id');
            if(!$ids) return $this->alert(false, lang('alert.id-empty'));

            /**
             *批量更新
             */
            if(is_array($ids)){
                return $this->batch(\App\Model\Admin::whereIn('id',$ids), array('status'=>Input::get('status')));
            }

            if(!$this->checkForm(Input::all(), ['password'])) return $this->alert();
            $admin =\App\Model\Admin::find($ids);
            if($admin->update(Input::all())){
                $this->status = true;
                $this->message = lang('alert.update_success', lang('group'));
            }else{
                $this->status = false;
                $this->message = lang('alert.update_failure', lang('group'));
            }

            return $this->alert($this->status, $this->message);
        }

        $id = Request::get('id');
        if(!$id) throw new NotFoundHttpException(lang('alert.404'));
        $admin = \App\Model\Admin::with('group')->find($id)->toArray();

        return Response::Json($admin);
	}

    /**
     * [delete删除]
     * @return mixed
     */
	public function delete()
	{
        $ids = (array) Input::get('id');
        $delete = \App\Model\Admin::whereIn('id', $ids)->delete();
        if($delete){
            $this->status = true;
            $this->message = lang('alert.delete_success');
        }else{
            $this->status = false;
            $this->message = lang('alert.delete_failure');
        }
        return $this->alert();
	}

}
