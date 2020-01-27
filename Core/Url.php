<?php

namespace Core;

class Url
{
    public $url;
    
    public function __construct()
    {
        $this->url = $_SERVER["REQUEST_URI"];
    }
}
