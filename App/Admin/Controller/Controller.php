<?php

namespace Admin\Controller;

use Lang;
use Config;
use Request;
use Response;
use Validator;

class Controller extends \PAO\Controller
{

    protected $status = false;

    protected $message = 'error';

    public function __construct()
    {
        parent::__construct();

        $this->assign('modules', $this->modules());
    }


    protected function auth()
    {
        return new Response('未登陆');
    }


    /**
     * 根据路由获取模块
     * @return array
     */
    protected function modules()
    {
        $modules = (array) Config::get('route');
        unset($modules['/']);
        return $modules;
    }

    /**
     * 验证表单
     */
    protected function checkForm($array = array(), $except = array())
    {
        if(!in_array('username',$except) && isset($array['username'])) Validator::make($array['username'])->username(Lang::get('alert.username'));
        if(!in_array('password',$except) && isset($array['password1'])) Validator::make($array['password1'])->password(Lang::get('alert.password'))->eq(Request::get('password2'), Lang::get('alert.password_distinct'));
        if(!in_array('name', $except) && isset($array['name'])) Validator::make($array['name'])->null(Lang::get('name').Lang::get('alert.empty'));
        if(!in_array('gid',$except) && isset($array['gid'])) Validator::make($array['gid'])->gt(1,sprintf(Lang::get('alert.select'), Lang::get('group')));
        if(!in_array('phone',$except) && isset($array['phone'])) Validator::make($array['phone'])->phone(sprintf(Lang::get('alert.phone')));
        if(!in_array('email',$except) && isset($array['email']))  Validator::make($array['email'])->email(sprintf(Lang::get('alert.email')));

        if(Validator::is(true)){
            return true;
        }else{
            $this->status = false;
            $this->message = Validator::error();
            return false;
        }
    }


    /**
     * @param null $status
     * @param null $message
     * @return mixed
     */
    protected function alert($status = null, $message = null)
    {
        if($status!==null && $message){
            return Response::Json(['status'=>$status, 'message'=>$message]);
        }
        return Response::Json(['status'=>$this->status, 'message'=>$this->message]);
    }


    /**
     * 批量更新操作
     * @param $model [要更新的model]
     * @param $array [要更新的数据]
     */
    protected function batch($query, array $array)
    {
        if($query->update($array)){
            return $this->alert(true, lang('alert.update_success'));
        }
        return $this->alert(false, lang('alert.update_failure'));
    }
}

