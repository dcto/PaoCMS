<?php

namespace PAO\Exception;

class LogicException extends \ErrorException
{
    protected $code = 412;

    protected $message = 'Logic Exception!';
}