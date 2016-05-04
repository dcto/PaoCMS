<?php

namespace App\Model;


use Illuminate\Support\Facades\Schema;



class Group extends Model
{

    protected $table = 'group';

    protected $primaryKey = 'id';

    protected $casts = ['permission'=>'array'];


    public function users()
    {
        return $this->belongsTo(__NAMESPACE__.'\\User', 'id');
    }

    static public function up()
    {
        Schema::create('group', function($table) {
            /** @var \Illuminate\Database\Schema\Blueprint $table */
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
        Schema::dropIfExists('group');
    }
}