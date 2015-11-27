<?php

namespace Nexus\Exception;

class NotFoundHttpException extends \RuntimeException
{

    public function getHttpCode()
    {
        return 404;
    }

    public function getHeaders()
    {
        return null;
    }

}