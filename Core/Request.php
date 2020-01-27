<?php


namespace Core;

class Request
{
    protected $attributes = [];

    public function __construct()
    {
        foreach ($_POST as $key => $value)
        {
            $this->attributes[$key] = $value;
        }
    }

    function __get($name)
    {
        return $this->attributes[$name];
    }
}