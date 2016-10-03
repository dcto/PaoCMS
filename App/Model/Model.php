<?php

namespace App\Model;

/**
 * Class Model
 * @package App\Model
 *
 */
abstract class Model extends \PAO\Model
{

    /**
     * 分页数
     * @var int
     */
    public $perPage = 10;

    /**
     * 自动维护时间 automatically maintained
     * @var bool
     */
    public $timestamps = true;

    /**
     * 是否开启软删除
     * @var bool
     */
    public $softDelete = true;
}