<?php

namespace PAO\Http;



class Request extends \Symfony\Component\HttpFoundation\Request
{


    public function url()
    {
        return $this->getUri();
    }

}


