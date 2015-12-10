<?php

namespace PAO;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * 表名称
     *
     * @var null
     */
    protected $table = null;


    /**
     * 字段黑名单(可以阻止被批量赋值)
     *
     * @var array
     */
    protected $guarded = ['*'];

    /**
     * 字段白名单  属性指定了哪些字段支持批量赋值 。可以设定在类的属性里或是实例化后设定。
     *
     * @var null
     */
    protected $fillable = [];

    /**
     * updated_at 和 created_at 数据库是否包含该两个字段，默认无false
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 默认日期格式
     * @var string
     */
    public $dateFormat = 'U';

}