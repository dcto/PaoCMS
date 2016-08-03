<?php

namespace App\Model;

use PAO\Support\Facades\DB;
use PAO\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class Group extends Model
{

    protected $table = 'group';

    protected $primaryKey = 'id';

    protected $casts = ['permission'=>'array'];

    public function users()
    {
        return $this->belongsTo(__NAMESPACE__.'\\User');
    }

    static public function up()
    {
        Schema::create('group', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->unsigned()->default(0)->comment('继承组id');
            $table->string('tag',32)->comment('组标签');
            $table->string('name',48)->comment('组名称');
            $table->string('nickname',96)->nullable()->comment('组头衔/别名');
            $table->text('permission')->nullable()->comment('组权限');
            $table->boolean('status')->default(0)->comment('状态');

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->engine = 'InnoDB';
        });

        $groups = array(
            ['id'=>'1', 'tag'=>'admin', 'name'=>'系统管理员', 'nickname'=>'超级管理员', 'status'=>1,'created_at'=>date('Y-m-d H:i:s')],
            ['id'=>'2', 'tag'=>'admin', 'name'=>'管理员', 'nickname'=>'管理员', 'status'=>1,'created_at'=>date('Y-m-d H:i:s')],
            ['id'=>'3', 'tag'=>'finance', 'name'=>'财务部', 'nickname'=>'财务部', 'status'=>1,'created_at'=>date('Y-m-d H:i:s')],
            ['id'=>'4', 'tag'=>'editor', 'name'=>'编辑部', 'nickname'=>'编辑部', 'status'=>1,'created_at'=>date('Y-m-d H:i:s')],
            ['id'=>'5', 'tag'=>'market', 'name'=>'市场部', 'nickname'=>'市场部', 'status'=>1,'created_at'=>date('Y-m-d H:i:s')],
        );
        DB::table('group')->insert($groups);
    }


    static public function down()
    {
        Schema::dropIfExists('group');
    }
}