<?php

namespace App\Model;

use Illuminate\Support\Facades\Schema;

class Setting extends Model
{

    protected $table = 'setting';


    static public function up()
    {

        Schema::create('setting', function($table){
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->increments('id')->unsigned();
            $table->string('key',24)->comment('键');
            $table->string('value')->nullable()->comment('值');

            $table->index('key');
            $table->engine = 'innodb';
        });
    }

    static public function down()
    {

       Schema::dropIfExists('setting');
    }

}
