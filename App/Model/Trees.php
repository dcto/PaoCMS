<?php

namespace App\Model;



use Illuminate\Database\Schema\Blueprint;
use PAO\Support\Facades\Schema;


class Trees extends Model
{
    protected $table = 'trees';

    protected $hidden = ['created_at', 'updated_at'];


    protected $appends = ['type'];


    public function getTypeAttribute()
    {
        if(isset($this->attributes['tag'])){
            return $this->attributes['tag'];
        }
        return 'default';
    }



    static public function getTreeById($id)
    {
        return self::where('pid', $id)->orderBy('order', 'ASC')->get()->toArray();
    }

    static public function getNodeById($id)
    {
        return self::where('id', $id)->orderBy('order', 'ASC')->first()->toArray();
    }

    static public function up()
    {
        Schema::create('trees', function(Blueprint $table){
            $table->increments('id');
            /*
            $table->integer('lft')->unsigned()->default(0)->comment('左');
            $table->integer('rgt')->unsigned()->default(0)->comment('右');
            $table->integer('root')->unsigned()->default(0)->comment('根');
            $table->integer('level')->unsigned()->default(0)->comment('层级');
            */
            $table->integer('pid')->unsigned()->default(0)->comment('父级');
            $table->string('tag',64)->nullable()->unique()->comment('类型');
            $table->string('name',96)->nullable()->comment('名称');
            $table->integer('level')->unsigned()->default(0)->comment('层级');
            $table->integer('order')->unsigned()->default(0)->comment('排序');
            $table->boolean('status')->default(0)->comment('0=停用,1=正常');

            $table->timestamps();
            $table->softDeletes();

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