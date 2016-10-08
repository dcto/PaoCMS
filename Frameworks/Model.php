<?php

namespace PAO;

use Illuminate\Container\Container;

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

        Container::getInstance()->make('db');

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


    protected function seeder()
    {
$sql=<<<EOF
DROP PROCEDURE IF EXISTS SEEDER;
CREATE DEFINER=`root`@`localhost` PROCEDURE `SEEDER`(IN db_name varchar(255), IN table_name varchar(255), IN rows bigint(255))
MAIN:BEGIN

declare v_maxcol integer;
	declare v_sql longtext;
	declare i integer;
 
	set @max=null;
	set @v_sql=null;
	set @success =-1;
 
	-- check parameters
	if db_name is null or table_name is null or rows is null or rows <=0 then
		leave main;
	elseif rows >50000000 then
		set rows =50000000;
	end if;
	
	-- get columns number
	select max(ordinal_position) into v_maxcol from information_schema.columns t where t.table_schema=db_name and t.table_name=table_name;
	if v_maxcol is null then leave main; end if;
 
	-- query maximum value of primary key or unique key
	select concat('select greatest(0,',group_concat(newcol separator ','),') from ',db_name,'.',table_name,' into @max') into v_sql from(
		select concat('max(',column_name,')') newcol
		from information_schema.columns t 
		where t.table_schema=db_name and t.table_name =table_name
			and data_type in('int','bigint','decimal','double','float','TINYINT') and column_key in('PRI','UNI')
	) t1;
 
	if v_sql is not null then
		set @v_sql =v_sql;
		prepare statm from @v_sql;
		execute statm;
	end if;
	if @max is null then set @max=0; end if;
 
	-- build insert statement
	set v_sql =concat('insert into ',db_name,'.',table_name);
	set v_sql =concat(v_sql,' select ');
	-- transform random value from columns to one row data
	set i=1;
	while i <=v_maxcol do
		set v_sql =concat(v_sql,'max(case when ordinal_position=',i,' then cdata end) cdata',i,',');
		set i =i+1;
	end while;
	-- build random value for every column
	set v_sql =concat(substr(v_sql,1,length(v_sql)-1),' from(
	SELECT ordinal_position,
	    case when column_key in(''PRI'',''UNI'') then @max
				when data_type in(''int'',''tinyint'',''smallint'',''bigint'',''decimal'',''double'',''float'') then rand()*power(10,
					case when numeric_precision-numeric_scale-1>9 then 9 else numeric_precision-numeric_scale-1 end)
				when data_type in(''char'',''varchar'') then concat(''X'',floor(rand()*power(10,
					case when t.character_maximum_length-1>30 then 30 else t.character_maximum_length-1 end)))
				when data_type in(''timestamp'',''datetime'',''date'',''time'') then now()
	    	else null
	    end cdata
	FROM information_schema.columns t
	WHERE table_schema =''',db_name,''' AND table_name =''',table_name,''' order by 1) a');
	set @v_sql =v_sql;
	prepare statm from @v_sql;
 
	-- loop insert with transaction per 10000 rows
	set i=1;
	start transaction;
	while i <=rows do
		set @max=@max+1;
		execute statm;
		set i =i+1;
		if mod(i,10000)=0 then commit;start transaction;end if;
	end while;
	commit;
 
	-- release resource
	deallocate prepare statm;
	
	-- select @v_sql;
 
	set @success =0;

END;
EOF;
        return $sql;
    }

}