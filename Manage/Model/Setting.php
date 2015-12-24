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
            $table->string('key',24)->comment('��');
            $table->json('value')->nullable()->comment('ֵ');

            $table->index('key');
            $table->engine = 'innodb';
        });
    }

    public function down()
    {

       Schema::dropIfExists('setting');
    }

}