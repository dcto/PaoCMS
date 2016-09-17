<?php

namespace PAO\Exception;

class SystemException extends \ErrorException
{
    protected $code = 500;

    protected $message = 'The PaoCMS System Was Error';
}