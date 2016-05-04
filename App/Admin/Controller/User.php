<?php

namespace Admin\Controller;

use DB;
use Config;
use Input;
use Lang;
use Request;
use Response;
use Crypt;
use PAO\Exception\NotFoundHttpException;

class User extends Controller
{

    /**
     * [index]
     * @return mixed
     */
    public function index()
	{

        $this->assign('title', lang('menu.user'));
        if(Input::has('w')){
            $w = trim(Input::get('w'));
            $assign['list'] = \App\Model\User::where('username', 'LIKE', "%$w%")
                ->Orwhere('email', 'LIKE', "%$w%")
                ->with('group')->paginate(Config::get('config.page'))->appends(Input::all('!page'));
        }else {
            $assign['list'] = \App\Model\User::with('group')->paginate(Config::get('config.page'));
        }
            $assign['group'] = \App\Model\Group::all();
	 	return Response::view('user', $assign);
	}

    /**
     * [detail详情]
     * @return mixed
     */
    public function detail()
    {
        $id = Request::get('id');
        return Response::view('user');
    }

    /**
     * [create创建]
     * @return mixed
     */
	public function create()
	{
        $admin = Input::all();
        if(!$this->checkForm($admin)) return $this->alert();
            $user = \App\Model\User::whereUsername(Input::get('username'))->first();
            if(!$user){
                $admin['password'] = md5(Crypt::encrypt(Input::get('password1')));

                $adminId = \App\Model\User::create($admin);
                if($adminId){
                    $this->status = true;
                    $this->message = lang('alert.create_success',lang('admin.title'));
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

        if(Request::isMethod('POST'))
        {

            $ids =  Input::get('id');
            if(!$ids) return $this->alert(false, lang('alert.id_empty'));

            /**
             *批量更新
             */
            if(is_array($ids)){
                \App\Model\User::whereIn('id', $ids)->update( array('status'=>Input::get('status')));
                return $this->alert(true, lang('alert.update_success', implode(',', $ids)));
            }
            $data = Input::not(['password1','password2']);
            if(Input::get('password1') && Input::get('password2')){
                if(!$this->checkForm(Input::all())) return $this->alert();

                $data['password'] = md5(Input::get('password2'));
            }else{
                if(!$this->checkForm(Input::all(),['password'])) return $this->alert();
            }
            $user =\App\Model\User::find($ids);
            if($user->update($data)){
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
        $user = \App\Model\User::with('group')->find($id)->toArray();

        return Response::Json($user);
	}

    /**
     * [delete删除]
     * @return mixed
     */
	public function delete()
	{
        $ids = (array) Input::get('id');
        $delete = \App\Model\User::whereIn('id', $ids)->delete();
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
