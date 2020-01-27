<?php

namespace App\Services;

use App\Models\Product;
use App\Models\TransportType;

class ShoppingCartService
{
    protected $products;

    public function __construct()
    {
        $this->products = (new Product)->findWhereIn(array_keys($_SESSION['shopping_cart']));
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function countSubTotalPrice()
    {
        $subTotalPrice = 0;

        foreach ($this->products as $product)
        {
            $subTotalPrice += $product->price * $_SESSION['shopping_cart'][$product->id];
        }

        return $subTotalPrice;
    }

    public function pay($transport_type_id)
    {
        $subTotalPrice = $this->countSubTotalPrice();
        $transportType = (new TransportType)->find($transport_type_id);

        if (!$transportType->id)
        {
            header('Location: /shopping-cart?error=1');
            exit;
        }

        $resultPrice = $subTotalPrice + $transportType->price;

        if ($_SESSION['cash'] >= $resultPrice && $resultPrice > 0)
        {
            $_SESSION['cash'] -= $resultPrice;
        }
        else
        {
            header('Location: /shopping-cart?error=1');
            exit;
        }

        $_SESSION['shopping_cart'] = [];

        return;
    }

    public function remove($product_id)
    {
        $product = (new Product)->find($product_id);

        if (!$product->id)
        {
            echo json_encode(['status' => 'error', 'message' => 'Product not found!']);

            return;
        }

        if (isset($_SESSION['shopping_cart'][$product_id]))
        {
            unset($_SESSION['shopping_cart'][$product_id]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Product removed!']);

        return;
    }

    public function change($product_id, $product_count)
    {
        $product = (new Product)->find($product_id);

        if (!$product->id)
        {
            echo json_encode(['status' => 'error', 'message' => 'Product not found!']);

            return;
        }

        if (isset($_SESSION['shopping_cart'][$product_id]))
        {
            $_SESSION['shopping_cart'][$product_id] = $product_count;
        }

        echo json_encode(['status' => 'success', 'message' => 'Product quantity changed!']);

        return;
    }

    public function add($product_id, $product_count)
    {
        $product = (new Product)->find($product_id);

        if (!$product->id)
        {
            echo json_encode(['status' => 'error', 'message' => 'Product not found!']);

            return;
        }

        if ($product_count <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong!']);

            return;
        }

        if (isset($_SESSION['shopping_cart'][$product_id]))
        {
            $_SESSION['shopping_cart'][$product_id] += $product_count;
        }
        else
        {
            $_SESSION['shopping_cart'][$product_id] = $product_count;
        }

        echo json_encode(['status' => 'success', 'message' => 'Product added!']);

        return;
    }
}