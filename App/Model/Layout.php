<?php

namespace App\Model;


use Illuminate\Database\Schema\Blueprint;


class Layout extends Model
{
    protected $table = 'layout';

    static public function up()
    {
        \Schema::create('layout', function(Blueprint $table) {
            $table->string('id',48)->comment('标识');
            $table->string('name',96)->nullable()->comment('名称');
            $table->string('title',96)->nullable()->comment('标题');
            $table->mediumText('content')->nullable()->comment('内容');
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
        \Schema::dropIfExists('layout');
    }
}