<?php

namespace App\Controllers;

use Core\View;
use App\Models\Product;

class ShoppingCartController {

    public function index()
    {
        return View::render('shopping_cart', []);
    }
}
