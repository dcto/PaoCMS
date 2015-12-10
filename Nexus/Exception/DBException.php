<?php

namespace PAO\Exception;


/**
 * DBÒì³£Àà
 */
class DBException extends \RuntimeException
{

    protected $sql;

    protected $message = 'Database Error';

    public function getSql()
    {
        return $this->sql;
    }


    private function  _getErrorHandle($message)
    {
        return PAOException::showError('DB', $message);
    }
}