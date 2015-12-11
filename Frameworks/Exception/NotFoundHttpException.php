<?php

namespace PAO\Exception;

class NotFoundHttpException extends \RuntimeException
{
    protected $message = '404 Not Found';


    public function getHttpCode()
    {
        return 404;
    }

    public function getHeaders()
    {
        return null;
    }

}