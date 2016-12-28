<?php

namespace PAO\Crypt\Driver;


abstract class CryptDriver
{

    /**
     * Encrypt String
     *
     * @param $string
     * @return string
     */
    public function en($string){}


    /**
     * Decrypt String
     *
     * @param $string
     * @return string
     */
    public function de($string){}

}