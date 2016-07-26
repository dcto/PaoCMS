<?php

namespace App\Model;

use Illuminate\Database\Schema\Blueprint;
use PAO\Support\Facades\Schema;

class Setting extends Model
{

    protected $table = 'setting';


    static public function up()
    {

        Schema::create('setting', function(Blueprint $table){
            $table->increments('id');
            $table->string('key',32)->comment('键');
            $table->string('value')->nullable()->comment('值');

            $table->timestamps();
            $table->softDeletes();

            $table->index('key');

            $table->engine = 'innodb';
        });
    }

    static public function down()
    {

       Schema::dropIfExists('setting');
    }

}
