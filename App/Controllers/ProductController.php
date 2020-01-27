<?php

namespace App\Controllers;

use Core\View;
use App\Models\Product;

class ProductController
{

    public function index()
    {
        $products = (new Product)->compositeLeftjoin('products.*, sum(ratings.rating)/count(ratings.rating) rating', 'ratings', 'products.id', 'product_id', 'products.id');

        return View::render('products.index', [
            'products' => $products,
        ]);
    }
}
