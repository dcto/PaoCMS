<?php

namespace PAO\Exception;

class ServiceException extends PAOException
{
    protected $code = 501;

    protected $message = '501 The PaoCMS System Service Was Error';

}