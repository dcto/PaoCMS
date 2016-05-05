<?php

namespace App\Model;



use Illuminate\Support\Facades\Schema;

class Trees extends Model
{
    protected $table = 'trees';

    protected $hidden = ['status','created_at', 'updated_at'];


    protected $appends = ['level'];

    public function getLevelAttribute()
    {
        return $this->attributes['pid'];
    }

    public function getTypeAttribute()
    {
        return 'default';
    }



    public function getTreeById($id, $with = [])
    {
    }

    static public function up()
    {
        Schema::create('trees', function($table){
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->increments('id')->unsigned();
            /*
            $table->integer('lft')->unsigned()->default(0)->comment('左');
            $table->integer('rgt')->unsigned()->default(0)->comment('右');
            $table->integer('root')->unsigned()->default(0)->comment('根');
            $table->integer('level')->unsigned()->default(0)->comment('层级');
            */
            $table->integer('pid')->unsigned()->default(0)->comment('父级');
            $table->string('tag',64)->nullable()->unique()->comment('类型');
            $table->string('name',96)->nullable()->comment('名称');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->boolean('status')->default(0)->comment('0=停用,1=正常');


            $table->unique(array('id','pid'));
            $table->index('pid');
            $table->index('tag');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }


    static public function down()
    {
        Schema::dropIfExists('trees');
    }


}