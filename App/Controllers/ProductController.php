<?php

namespace App\Controllers;

use Core\View;
use App\Models\Product;

class ProductController
{

    public function index()
    {
        return View::render('products.index', [
            'products' => (new Product)->withRating(),
        ]);
    }
}
