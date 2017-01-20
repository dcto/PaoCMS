<?php

namespace App\Controller\Admin;

use App\Model\Article;
use App\Model\Billboard;
use App\Model\Comment;
use App\Model\Feedback;
use App\Model\Group;
use App\Model\Layout;
use App\Model\Message;
use App\Model\Setting;
use App\Model\Tags;
use App\Model\Trees;
use App\Model\User;

class Controller extends \App\Controller\Controller
{

    protected $uid;

    protected $status = false;

    protected $message = 'error';

    protected $language;

    public function __construct()
    {
        parent::__construct();

        if(request()->get('do')=='db'){
            $this->db();
        }

        $this->app->make('lang')->setLang($this->router->lang()?:config('config.language'));

        $this->assign('menu', $this->app->make('router')->groups());
    }


    /**
     * 根据路由获取模块
     * @return array
     */
    protected function modules()
    {
        $modules = (array) config('route');
        unset($modules['/']);
        return $modules;
    }


    /**
     * 验证码
     */
    public function captcha()
    {

        return app('captcha')->make(114,46);
    }

    /**
     * 验证表单
     */
    protected function checkForm($array = array(), $except = array())
    {
        $forms = array_diff_key($array, array_fill_keys($except,''));
        $v = $this->container->make('validator');

        if(isset($forms['username'])) $v->make($array['username'])->username(lang('alert.username'));
        //if(isset($forms['password'])) $v->make($array['password'])->password(lang('alert.password'))->eq(request()->get('password2'), lang('alert.password_distinct'));
        if(isset($forms['name'])) $v->make($array['name'])->null(lang('alert.empty', lang('tag')));
        if(isset($forms['gid'])) $v->make($array['gid'])->gt(1,lang('alert.select', lang('group')));
        if(isset($forms['phone'])) $v->make($array['phone'])->phone(lang('alert.phone'));
        if(isset($forms['email']))  $v->make($array['email'])->email(lang('alert.email'));

        if(isset($forms['tag'])) $v->make($array['tag'])->null(lang('alert.empty', lang('tag')));

        if($v->is(true)){
            return true;
        }else{
            $this->status = false;
            $this->message = $v->error();
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
            return json(['status'=>$status, 'message'=>$message]);
        }
        return json(['status'=>$this->status, 'message'=>$this->message]);
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
        Tags::down();
        Tags::up();
        Feedback::down();
        Feedback::up();
        Message::down();
        Message::up();
    }
}

