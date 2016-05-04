<?php

namespace App\Model;


use Illuminate\Support\Facades\Schema;

class Article extends Model
{
    protected $table = 'layout';

    static public function up()
    {
        Schema::create('layout', function($table) {
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->string('id',48)->comment('标识');
            $table->string('name',96)->nullable->comment('名称');
            $table->string('title',96)->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->boolean('status')->default(0)->comment('0=无效,1=正常');

            $table->unique('id');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }

    static public function down()
    {
        Schema::dropIfExists('layout');
    }
}