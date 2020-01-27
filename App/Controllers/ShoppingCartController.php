<?php

namespace App\Controllers;

use Core\View;
use App\Models\Product;
use App\Models\TransportType;

class ShoppingCartController {

    public function index()
    {
        $products = Product::findWhereIn(array_keys($_SESSION['shopping_cart']));
        $transportTypes = TransportType::all();

        return View::render('shopping_cart', [
            'products' => $products,
            'subTotalPrice' => (new Product())->countSubTotalPrice($products),
            'transportTypes' => $transportTypes,
        ]);
    }

    public function add()
    {
        $product = Product::find($_POST['product_id']);
        if (!$product->id) {
            echo json_encode(['status' => 'error', 'message' => 'Product not found!']);

            return;
        }

        if (isset($_SESSION['shopping_cart'][$_POST['product_id']])) {
            $_SESSION['shopping_cart'][$_POST['product_id']] += $_POST['product_count'];
        } else {
            $_SESSION['shopping_cart'][$_POST['product_id']] = $_POST['product_count'];
        }

        echo json_encode(['status' => 'success', 'message' => 'Product added!']);

        return;
    }

    public function change()
    {
        $product = Product::find($_POST['product_id']);
        if (!$product->id) {
            echo json_encode(['status' => 'error', 'message' => 'Product not found!']);

            return;
        }

        if (isset($_SESSION['shopping_cart'][$_POST['product_id']])) {
            $_SESSION['shopping_cart'][$_POST['product_id']] = $_POST['product_count'];
        }
        echo json_encode(['status' => 'success', 'message' => 'Product quantity changed!']);

        return;
    }

    public function remove()
    {
        $product = Product::find($_POST['product_id']);
        if (!$product->id) {
            echo json_encode(['status' => 'error', 'message' => 'Product not found!']);

            return;
        }

        if (isset($_SESSION['shopping_cart'][$_POST['product_id']])) {
            unset($_SESSION['shopping_cart'][$_POST['product_id']]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Product removed!']);

        return;
    }

    public function pay()
    {

        $products = Product::findWhereIn(array_keys($_SESSION['shopping_cart']));
        $subTotalPrice = (new Product())->countSubTotalPrice($products);
        $transportType = TransportType::find($_POST['transport_type']);

        if (!$transportType->id) {
            header('Location: /shopping-cart?error=1');
            exit;
        }

        $resultPrice = $subTotalPrice + $transportType->price;

        if ($_SESSION['cash'] >= $resultPrice) {
            $_SESSION['cash'] -= $resultPrice;
        } else {
            header('Location: /shopping-cart?error=1');
            exit;
        }

        $_SESSION['shopping_cart'] = [];
        header('Location: /shopping-cart?pay=1');
        exit;
    }
}
