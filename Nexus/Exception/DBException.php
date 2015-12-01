<?php

namespace PAO\Exception;

use PAO\Exception\PAOException;

/**
 * DB�쳣��
 */
class DBException
{

    protected $sql;

    public function __construct($message, $code = 0, $sql = '')
    {
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