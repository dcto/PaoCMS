<?php

namespace App\Model;


use Illuminate\Support\Facades\Schema;



class AdminGroup extends Model
{

    protected $table = 'admin_group';

    protected $primaryKey = 'id';

    protected $casts = ['permission'=>'array'];


    public function admin()
    {
        return $this->belongsTo(__NAMESPACE__.'\\Admin', 'id');
    }

    static public function up()
    {
        Schema::create('admin_group', function($table) {
            $table->increments('id')->unsigned();
            $table->string('name',48)->comment('组名称');
            $table->text('permission')->comment('组权限');
            $table->boolean('status')->default(0)->comment('状态');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->index('status');
            $table->engine = 'innodb';
        });
    }


    static public function down()
    {
        Schema::dropIfExists('admin_group');
    }
}