<?php

namespace Manage\Model;


use Illuminate\Support\Facades\Schema;

class User extends Model
{


    protected $table = 'user';


    public function up()
    {
        Schema::create('user', function($table){

            $table->increments('id')->unsigned();
            $table->integer('pid')->unsigned()->default(0)->comment('父id');
            $table->integer('gid')->unsigned()->default(0)->comment('组id');
            $table->string('username',32)->comment('帐号');
            $table->string('password',32)->comment('密码');
            $table->string('nickname',32)->nullable()->comment('昵称');
            $table->string('email',96)->nullable()->comment('电子邮件');
            $table->string('phone',11)->nullable()->comment('手机');
            $table->string('tel',24)->nullable()->comment('电话');
            $table->string('fax',24)->nullable()->comment('传真');
            $table->string('qq',13)->nullable()->comment('qq');
            $table->string('address')->nullable()->comment('地址');
            $table->boolean('status')->default(0)->comment('0=停用,1=正常');

            $table->unique(array('id','pid'));
            $table->index('username');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }



    public function down()
    {
        Schema::dropIfExists('user');
    }
}