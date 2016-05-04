<?php

namespace App\Model;



use Illuminate\Support\Facades\Schema;

class Trees extends Model
{
    protected $table = 'trees';

    protected $hidden = ['status','created_at', 'updated_at'];

    /*
    protected $appends = ['level','type'];

    public function getLevelAttribute()
    {
        //return $this->attributes['pid'];
    }

    public function getTypeAttribute()
    {
        return 'default';
    }
    */

    static public function up()
    {
        Schema::create('trees', function($table){
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->increments('id')->unsigned();
            $table->integer('lft')->unsigned()->default(0)->comment('左');
            $table->integer('rgt')->unsigned()->default(0)->comment('右');
            $table->integer('root')->unsigned()->default(0)->comment('根');
            $table->integer('level')->unsigned()->default(0)->comment('层级');
            $table->string('type',64)->nullable()->comment('类型');
            $table->string('name',96)->nullable()->comment('名称');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->boolean('status')->default(0)->comment('0=停用,1=正常');

            $table->index('root');
            $table->index('type');
            $table->index('lft');
            $table->index('rgt');
            $table->index('level');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }


    static public function down()
    {
        Schema::dropIfExists('trees');
    }


}