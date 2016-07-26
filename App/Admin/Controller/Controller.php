<?php

namespace Admin\Controller;

use Lang;
use Config;
use Request;
use Response;
use Validator;


use App\Model\Article;
use App\Model\Billboard;
use App\Model\Comment;
use App\Model\Group;
use App\Model\Layout;
use App\Model\Setting;
use App\Model\Trees;
use App\Model\User;

class Controller extends \PAO\Controller
{

    protected $status = false;

    protected $message = 'error';

    public function __construct()
    {
        parent::__construct();

        if(Request::get('do')=='db'){
            $this->db();
        }

        $this->assign('menu', $this->container->make('router')->groups());
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
        $forms = array_diff_key($array, array_fill_keys($except,''));

        if(isset($forms['username'])) Validator::make($array['username'])->username(lang('alert.username'));
        if(isset($forms['password'])) Validator::make($array['password1'])->password(lang('alert.password'))->eq(Request::get('password2'), lang('alert.password_distinct'));
        if(isset($forms['name'])) Validator::make($array['name'])->null(lang('alert.empty', lang('tag')));
        if(isset($forms['gid'])) Validator::make($array['gid'])->gt(1,lang('alert.select', lang('group')));
        if(isset($forms['phone'])) Validator::make($array['phone'])->phone(lang('alert.phone'));
        if(isset($forms['email']))  Validator::make($array['email'])->email(lang('alert.email'));

        if(isset($forms['tag'])) Validator::make($array['tag'])->null(lang('alert.empty', lang('tag')));

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


    protected function db()
    {
        Article::down();
        Article::up();
        Billboard::down();
        Billboard::up();
        Comment::down();
        Comment::up();
        Layout::down();
        Layout::up();
        Setting::down();
        Setting::up();
        Trees::down();
        Trees::up();
        User::down();
        User::up();
        Group::down();
        Group::up();
    }
}

