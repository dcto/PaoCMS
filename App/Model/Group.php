<?php

namespace App\Model;

use PAO\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class Group extends Model
{

    protected $table = 'group';

    protected $primaryKey = 'id';

    protected $casts = ['permission'=>'array'];


    public function users()
    {
        return $this->belongsTo(__NAMESPACE__.'\\User');
    }

    static public function up()
    {
        Schema::create('group', function(Blueprint $table) {
            $table->increments('id');
            $table->string('tag',32)->comment('组标签');
            $table->string('name',48)->comment('组名称');
            $table->string('nickname',96)->nullable()->comment('组头衔/别名');
            $table->json('permission')->nullable()->comment('组权限');
            $table->boolean('status')->default(0)->comment('状态');

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }


    static public function down()
    {
        Schema::dropIfExists('group');
    }
}