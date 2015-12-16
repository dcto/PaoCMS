<?php

namespace PAO;

use Illuminate\Container\Container;

class Model extends \Illuminate\Database\Eloquent\Model
{

    /**
     * �ֶκ�����(������ֹ��������ֵ)
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * �ֶΰ�����  ����ָ������Щ�ֶ�֧��������ֵ �������趨��������������ʵ�������趨��
     *
     * @var null
     */
    protected $fillable = [];

    /**
     * updated_at �� created_at ���ݿ��Ƿ�����������ֶΣ�Ĭ����false
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * Ĭ�����ڸ�ʽ
     * @var string
     */
    public $dateFormat = 'U';


    /**
     * [table ��ȡ��������]
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
     * [boot �����¼��۲���]
     *
     * @author 11.
     */
    protected static function boot()
    {
        parent::boot();

        Container::getInstance()->make('db');

        /**
         * �����¼�
         */
        static::creating(function($event)
        {
            //exit('creating');
        });

        /**
         * �Ѵ����¼�
         */
        static::created(function($event)
        {
           // exit('created');
        });

        /**
         * �����¼�
         */
        static::updating(function($event)
        {
            exit('updating');
        });

        /**
         * �Ѹ����¼�
         */
        static::updated(function($event)
        {
            exit('updated');
        });

        /**
         * �����¼�
         */
        static::saving(function($event)
        {
            //exit('saving');
        });

        /**
         * �ѱ����¼�
         */
        static::saved(function ($event)
        {

            //exit('saved');

        });

        /**
         * ɾ���¼�
         */
        static::deleting(function($event)
        {
            //exit('deleting');
        });

        /**
         * ��ɾ���¼�
         */
        static::deleted(function($event)
        {
            //exit('deleted');
        });

    }

}