<?php

namespace PAO\Exception;

class SystemException extends \Exception
{
    protected $code = 500;

    protected $message = '500 The PaoCMS System Was Error';
}