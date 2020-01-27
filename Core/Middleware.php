<?php


namespace Core;


class Middleware
{
    public static function sessionStart()
    {
        session_start();

        if (!isset($_SESSION["cash"]))
            $_SESSION["cash"] = 100;

        if (!isset($_SESSION['shopping_cart']))
            $_SESSION['shopping_cart'] = [];

        if (!isset($_SESSION['rating']))
            $_SESSION['rating'] = [];
    }
}