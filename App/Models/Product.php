<?php

namespace App\Models;

use Core\Model;

class Product extends Model
{
    protected $table = 'products';

    public function withRating()
    {
        return $this->compositeLeftjoin(
            'products.*, sum(ratings.rating)/count(ratings.rating) rating',
            'ratings',
            'products.id',
            'product_id',
            'products.id'
        );
    }
}
