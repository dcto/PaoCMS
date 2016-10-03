<?php

namespace App\Model;

class Model extends \PAO\Model
{

    /**
     * 分页数
     * @var int
     */
    public $perPage = 10;

    public $timestamps = true;

    public $softDelete = true;
}