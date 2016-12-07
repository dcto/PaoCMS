<?php

namespace PAO\Exception;

class NotFoundException extends E
{
    protected $status = 404;

    protected $message = '404 Not Found';
}