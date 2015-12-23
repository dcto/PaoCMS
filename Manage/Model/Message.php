<?php

namespace Manage\Model;


use Illuminate\Support\Facades\Schema;


class Message extends Model
{
    protected $table = 'message';



    public function up()
    {
        Schema::create('message', function($table) {

            $table->increments('id')->unsigned();
            $table->integer('by_uid')->unsigned()->default(0)->comment('发送者uid');
            $table->integer('to_uid')->unsigned()->default(0)->comment('接收者uid');
            $table->string('title')->default(null)->comment('标题');
            $table->text('content')->comment('内容');
            $table->datetime('time')->comment('时间');
            $table->string('ip',24)->default(null)->comment('IP');
            $table->boolean('status')->default(0)->comment('0=无效,1=正常');

            $table->index('by_uid');
            $table->index('to_uid');
            $table->index('status');
            $table->engine = 'InnoDB';

        });
    }


    public function down()
    {
        Schema::dropIfExists('message');
    }

}