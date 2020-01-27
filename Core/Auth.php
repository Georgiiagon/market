<?php


namespace Core;


class Auth
{
    protected $attributes = [];

    public function __construct()
    {
        $this->attributes['id'] = $_SESSION['user_id'];
        $this->attributes['last_name'] = $_SESSION['user_last_name'];
        $this->attributes['first_name'] = $_SESSION['user_first_name'];
    }

    function __get($name)
    {
        return $this->attributes[$name];
    }

    public function user()
    {
        return $this;
    }

    public function loggedin()
    {
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
        {
            return true;
        }

        return false;
    }
}