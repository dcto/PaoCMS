<?php

namespace Manage\Model;



use Illuminate\Support\Facades\Schema;

class Category extends Model
{
    protected $table = 'category';


    public function up()
    {
        Schema::create('category', function($table){
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->increments('id')->unsigned();
            $table->integer('pid')->default(0)->unsigned()->comment('父id');
            $table->string('name',64)->comment('名称');
            $table->boolean('status')->default(0)->comment('0=停用,1=正常');

            $table->engine = 'InnoDB';

        });
    }


    public function down()
    {
        Schema::dropIfExists('category');
    }


}