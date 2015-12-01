<?php

namespace PAO\Exception;

class ServiceException extends \RuntimeException
{
    protected $message = '501 The PaoCMS System Service Was Error';

    public function getHttpCode()
    {
        return 501;
    }

    public function getHeaders()
    {
        return null;
    }

}