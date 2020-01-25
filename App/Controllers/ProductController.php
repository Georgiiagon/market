<?php

namespace App\Controllers;

use Core\View;
use App\Models\Product;

class ProductController {

    public function index()
    {
        $products = Product::get();

        return View::render('products.index', [
            'products' => $products,
        ]);
    }
}
