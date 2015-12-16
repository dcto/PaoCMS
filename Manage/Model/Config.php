<?php

namespace Manage\Model;

use Illuminate\Support\Facades\Schema;

class Config extends Model
{

    protected $table = 'config';


    public function up()
    {

        Schema::create('config', function($table){
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->increments('id')->unsigned();
            $table->string('key',24);
            $table->json('value');

        });
    }

    public function down()
    {

       Schema::dropIfExists('config');
    }

}