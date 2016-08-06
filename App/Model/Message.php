<?php

namespace App\Model;

use Illuminate\Database\Schema\Blueprint;
use PAO\Support\Facades\Schema;

class Message extends Model
{

    protected $table = 'message';


    static public function up()
    {

        Schema::create('message', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('gid')->default(0)->comment('接收组');
            $table->unsignedInteger('by_uid')->default(0)->comment('发送者:0=系统发送,组消息');
            $table->unsignedInteger('to_uid')->default(0)->comment('接收者:0=系统消息,组消息');
            $table->string('title',64)->comment('标题');
            $table->text('content')->comment('内容');
            $table->boolean('status')->default(0)->comment('状态:0=未读,1已读');

            $table->timestamps();
            $table->softDeletes();

            $table->index('gid');
            $table->index('by_uid');
            $table->index('to_uid');
            $table->index('status');

            $table->engine = 'innodb';
        });
    }

    static public function down()
    {

       Schema::dropIfExists('message');
    }

}
