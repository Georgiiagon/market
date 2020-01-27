<?php

namespace App\Controllers;

use Core\View;
use App\Models\TransportType;

use App\Services\ShoppingCartService;
use Core\Request;

class ShoppingCartController
{
    protected $service;

    public function __construct()
    {
        $this->service = new ShoppingCartService();
    }

    public function index()
    {
        return View::render('shopping_cart', [
            'products' => $this->service->getProducts(),
            'subTotalPrice' => $this->service->countSubTotalPrice(),
            'transportTypes' => (new TransportType)->all(),
        ]);
    }

    public function add()
    {
        $request = new Request();

        $this->service->add($request->product_id, $request->product_count);

        return;
    }

    public function change()
    {
        $request = new Request();

        $this->service->change($request->product_id, $request->product_count);

        return;
    }

    public function remove()
    {
        $request = new Request();

        $this->service->remove($request->product_id);

        return;
    }

    public function pay()
    {
        $request = new Request();

        $this->service->pay($request->transport_type);

        header('Location: /shopping-cart?pay=1');
        exit;
    }
}
