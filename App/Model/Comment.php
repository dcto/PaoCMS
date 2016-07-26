<?php

namespace App\Model;


use Illuminate\Database\Schema\Blueprint;
use PAO\Support\Facades\Schema;

class Comment extends Model
{
    protected $table = 'comment';

    public function article()
    {
        return $this->hasOne(__NAMESPACE__.'\\Article');
    }

    public function user()
    {
        return $this->hasOne(__NAMESPACE__.'\\User');
    }


    static public function up()
    {
        Schema::create('comment', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->default(0)->comment('引用用户id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户id');
            $table->unsignedInteger('article_id')->default(0)->comment('文章id');
            $table->string('email',96)->nullable()->comment('邮箱');
            $table->string('title',96)->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->boolean('status')->default(0)->comment('0=无效,1=正常');

            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('user_id')->references('id')->on('user');
            //$table->foreign('article_id')->references('id')->on('article');
            $table->index('pid');
            $table->index('user_id');
            $table->index('article_id');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }

    static public function down()
    {
        Schema::dropIfExists('comment');
    }
}