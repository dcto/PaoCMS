<?php

namespace App\Model;

use Illuminate\Support\Facades\Schema;

class Admin extends  Model
{

    protected $table = 'billboard';

    protected $primaryKey = 'id';

    public $perPage = 10;


    static public function up()
    {
        Schema::create(self::$table, function($table){
            $table->increments('id')->unsigned();
            $table->integer('pid')->unsigned()->comment('父id');
            $table->integer('gid')->unsigned()->comment('组id');
            $table->string('tag',32)->default()->comment('标签');
            $table->string('name',96)->default()->comment('名称');
            $table->string('link')->default()->comment('链接');
            $table->string('image')->default()->comment('图片');
            $table->string('content',15)->default()->comment('备注');
            $table->integer('times')->unsigned()->default(0)->comment('点击次数');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->boolean('status')->default(0)->comment('0=停用,1=正常');

            $table->index('pid');
            $table->index('gid');
            $table->index('tag');
            $table->index('status');
            $table->engine = 'InnoDB';

        });
    }


    static public function down()
    {
        Schema::dropIfExists(self::$table);
    }

}