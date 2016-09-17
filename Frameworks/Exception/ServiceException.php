<?php

namespace PAO\Exception;

class ServiceException extends \ErrorException
{
    protected $code = 501;

    protected $message = 'The service was error';

}