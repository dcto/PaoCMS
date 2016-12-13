<?php

namespace PAO;


abstract class Model extends \Illuminate\Database\Eloquent\Model
{

    /**
     * 字段黑名单(可以阻止被批量赋值)
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at','deleted_at'];

    /**
     * 字段白名单  属性指定了哪些字段支持批量赋值 。可以设定在类的属性里或是实例化后设定。
     *
     * @var null
     */
    protected $fillable = [];


    /**
     * 默认日期格式
     * @var string
     */
    protected $dateFormat;


    /**
     * 预定义分页数
     * @var int
     */
    protected $perPage = 10;


    /**
     * 预定义联查
     * @var array
     */
    protected $with = [];


    /**
     *
     * 数组转换 把数组转化成JSON格式存入数据库 读取时自动转化成数组
     * @var array
     */
    protected $casts = [];

    /**
     * 追加字段到返回数组中 而且是数据库没有的字段 而且需要访问器的帮忙
     * 但这个不理解有什么用处 他其实是通过已有字段经过判断后输出 两个字段都能返回 只不过这个返回是布尔值
     * @var array
     */
    protected $appends = [];

    /**
     * 隐藏模型的一些属性 直接输出的时候是无法看见的
     * @var array
     */
    protected $hidden = [];


    /**
     * 显示白名单 那些字段直接输出是可以被看到的
     * @var array
     */
    protected $visible = [];

    /**
     * updated_at 和 created_at 数据库是否包含该两个字段，默认无false
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * [table 获取表名方法]
     *
     * @return mixed
     * @author 11.
     * @example \App\Model\Test::table();
     */
    public static function table()
    {
        return (new static)->getTable();
    }


    public function toArray()
    {
        $attributes = $this->attributesToArray();

        return array_merge($attributes, (array) $this->relationsToArray());
    }
    

    /**
     * [boot 启动事件观察器]
     *
     * @author 11.
     */
    protected static function boot()
    {
        parent::boot();

        app('db');

        /**
         * 创建事件
         */
        static::creating(function($event)
        {

        });

        /**
         * 已创建事件
         */
        static::created(function($event)
        {

        });

        /**
         * 更新事件
         */
        static::updating(function($event)
        {

        });

        /**
         * 已更新事件
         */
        static::updated(function($event)
        {

        });

        /**
         * 保存事件
         */
        static::saving(function($event)
        {

        });

        /**
         * 已保存事件
         */
        static::saved(function ($event)
        {

        });

        /**
         * 删除事件
         */
        static::deleting(function($event)
        {

        });

        /**
         * 已删除事件
         */
        static::deleted(function($event)
        {

        });

    }

}