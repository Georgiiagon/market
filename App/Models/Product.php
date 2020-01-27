<?php

namespace App\Models;

use Core\Model;

class Product extends Model
{
	protected $table = 'products';

	public function countSubTotalPrice($products)
    {
        $subTotalPrice = 0;

        foreach ($products as $product)
        {
            $subTotalPrice += $product->price * $_SESSION['shopping_cart'][$product->id];
        }

        return $subTotalPrice;
    }
}
