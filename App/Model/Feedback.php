<?php

namespace App\Model;


use Illuminate\Database\Schema\Blueprint;
use PAO\Support\Facades\Schema;

class Feedback extends Model
{
    protected $table = 'feedback';

    static public function up()
    {
        Schema::create('feedback', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->comment('父ID');
            $table->string('name',32)->nullable()->comment('名称');
            $table->string('email',96)->nullable()->comment('Email');
            $table->string('title',96)->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->boolean('status')->default(0)->comment('0=无效,1=正常');

            $table->timestamps();
            $table->softDeletes();

            $table->unique('id');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }

    static public function down()
    {
        Schema::dropIfExists('feedback');
    }
}