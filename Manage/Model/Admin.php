<?php

namespace Manage\Model;


use Illuminate\Container\Container;
use Illuminate\Support\Facades\Schema;

class Admin extends  Model
{

    protected $table = 'admin';

    protected $primaryKey = 'id';


    public function group()
    {
        return $this->hasOne('Manage\Model\AdminGroup','id','gid');
    }


    public function up()
    {

        Schema::create('admin', function($table){
            $table->increments('id')->unsigned();
            $table->integer('gid')->unsigned()->comment('组id');
            $table->string('username', 32)->unique()->comment('帐号');
            $table->string('password', 32)->comment('密码');
            $table->string('email',96)->nullable()->comment('电子邮件');
            $table->string('phone',15)->nullable()->comment('电话');
            $table->datetime('join_time')->nullable()->comment('注册时间');
            $table->datetime('last_time')->nullable()->comment('最后登陆时间');
            $table->string('join_ip',15)->nullable()->comment('注册IP');
            $table->string('last_ip',15)->nullable()->comment('最后登陆IP');

            $table->boolean('status')->default(0)->comment('0=停用,1=正常');
            $table->engine = 'InnoDB';

        });
    }


    public function down()
    {
        Schema::dropIfExists('admin');
    }


}