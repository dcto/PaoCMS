<?php

namespace PAO\Exception;



class SystemException extends \RuntimeException
{
    protected $message = '500 The System Was Error';

    public function getHttpCode()
    {
        return 500;
    }

    public function getHeaders()
    {
        return null;
    }

}