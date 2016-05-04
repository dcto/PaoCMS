<?php

namespace App\Model;


use Illuminate\Support\Facades\Schema;

class Article extends Model
{
    protected $table = 'comment';

    public function article()
    {
        return $this->hasOne(__NAMESPACE__.'\\Article','id','aid');
    }

    public function user()
    {
        return $this->hasOne(__NAMESPACE__.'\\User','id','uid');
    }


    static public function up()
    {
        Schema::create('comment', function($table) {
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->increments('id')->unsigned();
            $table->integer('aid')->unsigned()->default(0)->comment('文章id');
            $table->integer('uid')->unsigned()->default(0)->comment('用户id');
            $table->integer('pid')->unsigned()->default(0)->comment('引用用户id');
            $table->string('title',96)->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->boolean('status')->default(0)->comment('0=无效,1=正常');


            $table->index('aid');
            $table->index('uid');
            $table->index('pid');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }

    static public function down()
    {
        Schema::dropIfExists('comment');
    }
}