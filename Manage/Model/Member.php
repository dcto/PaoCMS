<?php

namespace Manage\Model;


use Illuminate\Support\Facades\Schema;

class Member extends Model
{


    protected $table = 'member';


    public function up()
    {
        Schema::create('member', function($table){

            $table->increments('id')->unsigned();
            $table->integer('pid')->unsigned()->comment('父id');
            $table->integer('gid')->unsigned()->comment('组id');
            $table->string('username',32)->comment('帐号');
            $table->string('password',32)->comment('密码');
            $table->string('nickname',32)->comment('昵称');
            $table->string('email',96)->comment('电子邮件');
            $table->string('phone',11)->comment('手机');
            $table->string('tel',24)->comment('电话');
            $table->string('fax',24)->comment('传真');
            $table->string('qq',13)->comment('qq');
            $table->string('address')->comment('地址');
            $table->boolean('status')->comment('0=停用,1=正常');

            $table->unique(array('id','pid'));
            $table->index('username');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }



    public function down()
    {
        Schema::dropIfExists('member');
    }
}