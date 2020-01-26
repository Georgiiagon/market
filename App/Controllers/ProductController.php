<?php

namespace App\Controllers;

use Core\View;
use App\Models\Product;

class ProductController {

    public function index()
    {
        $products = Product::all();
        var_dump($_SESSION['cash']);

        return View::render('products.index', [
            'products' => $products,
        ]);
    }
}
