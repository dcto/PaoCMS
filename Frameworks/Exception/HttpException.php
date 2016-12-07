<?php

namespace PAO\Exception;

class NotFoundException extends E
{
    protected $status = 500;

    protected $message = 'HTTP Error!';
}