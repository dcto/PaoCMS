<?php

namespace PAO\Http;

use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;


class Session extends  \Symfony\Component\HttpFoundation\Session\Session
{

    public function __construct(Container $container)
    {
        parent::__construct(null, new  AttributeBag('pao_'));
        
    }

}