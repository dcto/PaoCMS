<?php

namespace PAO\Exception;

class SystemException extends E
{
    protected $status = 500;

    protected $message = 'The PaoCMS System Was Error';
}