<?php

namespace Manage\Model;

use Illuminate\Support\Facades\Schema;

class Setting extends Model
{

    protected $table = 'setting';


    public function up()
    {

        Schema::create('setting', function($table){
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->increments('id')->unsigned();
            $table->string('key',24);
            $table->json('value')->nullable();

        });
    }

    public function down()
    {

       Schema::dropIfExists('setting');
    }

}