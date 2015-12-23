<?php

namespace Manage\Model;


use Illuminate\Support\Facades\Schema;

class Article extends Model
{
    protected $table = 'article';

    public function up()
    {
        Schema::create('article', function($table) {

            $table->increments('id')->unsigned();
            $table->integer('uid')->unsigned()->default(0)->comment('用户id');
            $table->integer('cid')->unsigned()->default(0)->comment('分类id');
            $table->string('tag')->nullable()->comment('标签');
            $table->string('from',96)->nullable()->comment('来源');
            $table->string('title',96)->nullable()->comment('标题');
            $table->string('author',64)->nullable()->comment('作者');
            $table->text('content')->nullable()->comment('内容');
            $table->datetime('join_time')->nullable()->comment('发表时间');
            $table->datetime('last_time')->nullable()->comment('修改时间');
            $table->string('join_ip',15)->nullable()->comment('发布时IP');
            $table->string('last_ip',15)->nullable()->comment('编辑后IP');
            $table->boolean('status')->default(0)->comment('0=无效,1=正常');

            $table->index('cid');
            $table->index('uid');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }

    public function down()
    {
        Schema::dropIfExists('article');
    }
}