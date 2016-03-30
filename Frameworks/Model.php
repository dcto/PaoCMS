<?php

namespace PAO;

use Illuminate\Container\Container;

class Model extends \Illuminate\Database\Eloquent\Model
{

    /**
     * 字段黑名单(可以阻止被批量赋值)
     *
     * @var array
     */
    protected $guarded = ['id'];

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


    /**
     * 预定义分页数
     * @var int
     */
    public $perPage = 10;


    /**
     * 预定义联查
     * @var array
     */
    public $with = [];

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


    /**
     * [boot 启动事件观察器]
     *
     * @author 11.
     */
    protected static function boot()
    {
        parent::boot();

        Container::getInstance()->make('db');

        /**
         * 创建事件
         */
        static::creating(function($event)
        {
            //exit('creating');
        });

        /**
         * 已创建事件
         */
        static::created(function($event)
        {
           //exit('created');
        });

        /**
         * 更新事件
         */
        static::updating(function($event)
        {
            //exit('updating');
        });

        /**
         * 已更新事件
         */
        static::updated(function($event)
        {
            //exit('updated');
        });

        /**
         * 保存事件
         */
        static::saving(function($event)
        {
            //exit('saving');
        });

        /**
         * 已保存事件
         */
        static::saved(function ($event)
        {

            //exit('saved');

        });

        /**
         * 删除事件
         */
        static::deleting(function($event)
        {
            //exit('deleting');
        });

        /**
         * 已删除事件
         */
        static::deleted(function($event)
        {
            //exit('deleted');
        });

    }

}