<?php

namespace App\Model;

use Illuminate\Support\Facades\Schema;

class Admin extends  Model
{

    protected $table = 'admin';

    protected $hidden = ['password'];

    protected $guarded = ['id','password1', 'password2'];

    protected $primaryKey = 'id';

    public $perPage = 10;

    public function group()
    {
        return $this->hasOne(__NAMESPACE__.'\\AdminGroup','id','gid');
    }


    static public function up()
    {

        Schema::create('admin', function($table){
            $table->increments('id')->unsigned();
            $table->integer('gid')->unsigned()->comment('组id');
            $table->string('username', 32)->unique()->comment('帐号');
            $table->string('password', 32)->comment('密码');
            $table->string('email',96)->default()->comment('电子邮件');
            $table->string('phone',15)->default()->comment('电话');
            $table->integer('times')->unsigned()->default(0)->comment('登录次数');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->boolean('status')->default(0)->comment('0=停用,1=正常');

            $table->index('gid');
            $table->index('status');
            $table->engine = 'InnoDB';

        });
    }


    static public function down()
    {
        Schema::dropIfExists('admin');
    }


}
