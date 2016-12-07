<?php

namespace PAO\Exception;

class ServiceException extends E
{
    protected $status = 501;

    protected $message = 'The service was error';
}