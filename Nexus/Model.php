<?php

namespace PAO;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * ������
     *
     * @var null
     */
    protected $table = null;


    /**
     * �ֶκ�����(������ֹ��������ֵ)
     *
     * @var null
     */
    protected $guarded = null;

    /**
     * �ֶΰ�����  ����ָ������Щ�ֶ�֧��������ֵ �������趨��������������ʵ�������趨��
     *
     * @var null
     */
    protected $fillable = null;

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

}