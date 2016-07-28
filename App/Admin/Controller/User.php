<?php

namespace Admin\Controller;

use PAO\Exception\NotFoundHttpException;
use PAO\Http\Request;

class User extends Controller
{

    /**
     * [index]
     * @return mixed
     */
    public function index(Request $request)
	{

        $this->assign('title', lang('menu.user'));
        if($request->has('w')){
            $w = trim($request->get('w'));
            $assign['list'] = \App\Model\User::where('username', 'LIKE', "%$w%")
                ->Orwhere('email', 'LIKE', "%$w%")
                ->with('group')->paginate(config('config.page'))->appends($request->all('!page'));
        }else {
            $assign['list'] = \App\Model\User::with('group')->paginate(config('config.page'));
        }
            $assign['group'] = \App\Model\Group::all();
	 	return view('user', $assign);
	}

    /**
     * [detail详情]
     * @return mixed
     */
    public function detail(Request $request)
    {
        $id = $request->get('id');
        return view('user');
    }

    /**
     * [create创建]
     * @return mixed
     */
	public function create(Request $request)
	{
        if(!$request->isMethod('POST')) return \Response::redirect(url('@user').'#create');
        $admin = $request->all();
        if(!$this->checkForm($admin)) return $this->alert();
            $user = \App\Model\User::whereUsername($request->get('username'))->first();
            if(!$user){
                $admin['password'] = md5(\Crypt::encrypt($request->get('password1')));

                $adminId = \App\Model\User::create($admin);
                if($adminId){
                    $this->status = true;
                    $this->message = lang('alert.create_success',lang('admin.title'));
                }else{
                   $this->message = lang('alert.create_failure', lang('admin.title'));
                }
            }else{
                $this->message = lang('alert.exist', $request->get('username'));
            }

        return $this->alert();
	}

    /**
     * [update更新]
     * @return mixed
     */
	public function update(Request $request)
	{

        if($request->isMethod('POST'))
        {

            $ids =  $request->get('id');
            if(!$ids) return $this->alert(false, lang('alert.id_empty'));

            /**
             *批量更新
             */
            if(is_array($ids)){
                \App\Model\User::whereIn('id', $ids)->update( array('status'=>$request->get('status')));
                return $this->alert(true, lang('alert.update_success', implode(',', $ids)));
            }
            $data = $request->not(['password1','password2']);
            if($request->get('password1') && $request->get('password2')){
                if(!$this->checkForm($request->all())) return $this->alert();

                $data['password'] = md5($request->get('password2'));
            }else{
                if(!$this->checkForm($request->all(),['password'])) return $this->alert();
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

        $id = $request->get('id');
        if(!$id) throw new NotFoundHttpException(lang('alert.404'));
        $user = \App\Model\User::with('group')->find($id)->toArray();

        return \Response::Json($user);
	}

    /**
     * [delete删除]
     * @return mixed
     */
	public function delete(Request $request)
	{
        $ids = (array) $request->get('id');
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
