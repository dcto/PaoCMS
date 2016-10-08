<?php

namespace App\Model;

use Illuminate\Database\Schema\Blueprint;


class Tags extends Model
{

    protected $table = 'tags';


    static public function up()
    {

        \Schema::create('tags', function(Blueprint $table){
            $table->increments('id');
            $table->string('name',32)->comment('TAG名称');
            $table->integer('hits')->unsigned()->default(0)->comment('点击次数');

            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');

            $table->engine = 'innodb';
        });
    }

    static public function down()
    {

       \Schema::dropIfExists('tags');
    }

}
