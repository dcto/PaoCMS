<?php

namespace App\Model;


use Illuminate\Database\Schema\Blueprint;

class User extends Model
{

    protected $table = 'user';


    public function group()
    {
        return $this->hasOne(__NAMESPACE__.'\\Group','id');
    }

    static public function up()
    {
        \Schema::create('user', function(Blueprint $table){
            $table->increments('id');
            $table->integer('pid')->unsigned()->default(0)->comment('父id');
            $table->integer('group_id')->unsigned()->default(0)->comment('组id');
            $table->string('username',32)->comment('帐号');
            $table->string('password',32)->comment('密码');
            $table->string('nickname',32)->nullable()->comment('昵称');
            $table->string('subtitle',96)->nullable()->comment('签名/副标题');
            $table->string('avatar',96)->nullable()->comment('头像');
            $table->string('site',96)->nullable()->comment('个人主页');
            $table->string('email',96)->nullable()->comment('电子邮件');
            $table->string('phone',24)->nullable()->comment('手机');
            $table->string('tel',24)->nullable()->comment('电话');
            $table->string('fax',24)->nullable()->comment('传真');
            $table->string('qq',13)->nullable()->comment('qq');
            $table->string('weibo',32)->nullable()->comment('新浪微博');
            $table->string('weixin',32)->nullable()->comment('微信号');
            $table->string('address')->nullable()->comment('地址');
            $table->text('content')->nullable()->comment('会员详情');
            $table->string('ip',24)->nullable()->comment('ip地址');
            $table->integer('times')->unsigned()->default(0)->comment('登录次数');
            $table->string('token',12)->nullable()->comment('用户标识码');
            $table->boolean('status')->default(0)->comment('0=停用,1=正常');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(array('id','pid'));
            $table->unique('username');
            $table->unique('email');
            $table->unique('phone');
            $table->index('pid');
            $table->index('group_id');
            $table->index('status');

            $table->engine = 'InnoDB';
        });
    }



    static public function down()
    {
        \Schema::dropIfExists('user');
    }

}