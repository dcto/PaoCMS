<?php

namespace App\Model;

use Illuminate\Database\Schema\Blueprint;

class Billboard extends  Model
{

    protected $table = 'billboard';

    static public function up()
    {
        \Schema::create('billboard', function(Blueprint $table){
            $table->increments('id')->unsigned();
            $table->integer('pid')->unsigned()->comment('父id');
            $table->integer('gid')->unsigned()->comment('组id');
            $table->string('tag',32)->default()->comment('标签');
            $table->string('name',96)->default()->comment('名称');
            $table->string('link')->default()->comment('链接');
            $table->string('title')->default()->comment('标题');
            $table->string('image')->default()->comment('图片');
            $table->string('content')->default()->comment('内容');
            $table->integer('times')->unsigned()->default(0)->comment('点击次数');
            $table->boolean('status')->default(0)->comment('0=停用,1=正常');

            $table->timestamps();
            $table->softDeletes();

            $table->index('pid');
            $table->index('gid');
            $table->index('tag');
            $table->index('status');
            $table->engine = 'InnoDB';

        });
    }


    static public function down()
    {
        \Schema::dropIfExists('billboard');
    }

}