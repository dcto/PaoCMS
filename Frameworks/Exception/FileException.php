<?php

namespace PAO\Exception;

class FileException extends E
{
    protected $status = 403;

    protected $message = 'File Error!';
}