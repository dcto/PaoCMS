<?php

namespace Portal\Model;


use Illuminate\Support\Facades\Schema;
use PAO\Model;

class Test extends Model
{
    protected $table = 'test';



    public function up()
    {
        Schema::create('test', function($table){

            $table->increments('id');

        });
    }
}