<?php

namespace Manage\Model;


use Illuminate\Support\Facades\Schema;



class AdminGroup extends \PAO\Model
{

    protected $table = 'admin_group';



    public function up()
    {
        Schema::create('admin_group', function($table) {
            $table->increments('id')->unsigned();
            $table->string('name',48)->comment('组名称');
            $table->text('permission')->comment('组权限');
            $table->boolean('status')->comment('状态');
        });
    }


    public function down()
    {
        Schema::dropIfExists('admin_group');
    }
}