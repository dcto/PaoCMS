<?php

namespace PAO\Exception;

class LogicException extends \Exception
{
    protected $code = 412;

    protected $message = 'Logic Exception!';
}