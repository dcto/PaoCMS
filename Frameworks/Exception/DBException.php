<?php

namespace PAO\Exception;


class DBException extends \RuntimeException
{
    protected $code = 503;

    protected $message = 'Database Error!';

}