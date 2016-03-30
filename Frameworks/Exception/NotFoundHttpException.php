<?php

namespace PAO\Exception;

class NotFoundHttpException extends \Exception
{
    protected $code = 404;

    protected $message = '404 Not Found';
}