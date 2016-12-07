<?php

namespace PAO\Exception;

class LogicException extends E
{
    protected $status = 500;

    protected $message = 'Logic Exception!';
}