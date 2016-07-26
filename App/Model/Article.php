<?php

namespace App\Model;


use Illuminate\Database\Schema\Blueprint;
use PAO\Support\Facades\Schema;

class Article extends Model
{
    protected $table = 'article';


    public function user()
    {
        return $this->hasOne(__NAMESPACE__.'\\User');
    }

    static public function up()
    {
        Schema::create('article', function(Blueprint $table) {

            $table->increments('id')->unsigned();
            $table->integer('pid')->unsigned()->default(0)->comment('父文pid');
            $table->integer('tags')->unsigned()->default(0)->comment('标签');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->string('title',96)->nullable()->comment('标题');
            $table->string('subtitle',96)->nullable()->comment('副标题');
            $table->string('from',48)->nullable()->comment('来源');
            $table->string('link')->nullable()->comment('链接');
            $table->string('author',64)->nullable()->comment('作者');
            $table->decimal('price')->unsigned()->comment('售价');
            $table->string('cover',96)->nullable()->comment('封面');
            $table->string('summary')->nullable()->comment('摘要');
            $table->text('content')->nullable()->comment('内容');
            $table->string('password',32)->nullable()->comment('阅读权限,非空表示须要密码');
            $table->integer('zan')->unsigned()->default(0)->comment('点赞次数');
            $table->integer('hit')->unsigned()->default(0)->comment('点击次数');
            $table->boolean('status')->default(0)->comment('0=无效,1=正常');

            $table->timestamp('overdue_at')->nullable()->comment('过期时间');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }

    static public function down()
    {
        Schema::dropIfExists('article');
    }
}