<?php

namespace Nexus\Exception;

use Nexus\Exception\PAOException;

/**
 * DB�쳣��
 */
class DBException
{

    protected $sql;

    public function __construct($message, $code = 0, $sql = '')
    {

        echo $this->_getErrorHandle($message);
    }

    public function getSql()
    {
        return $this->sql;
    }


    private function  _getErrorHandle($message)
    {
        return PAOException::showError('DB', $message);
    }
}