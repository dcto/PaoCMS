<?php

namespace PAO\Exception;

class NotFoundHttpException extends \Exception
{
    protected $code = 500;

    protected $message = 'HTTP Error!';
}