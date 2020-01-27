<?php

namespace App\Controllers;

use App\Services\RatingService;
use Core\Request;

class RatingController
{
    protected $service;

    public function __construct()
    {
        $this->service = new RatingService();
    }

    public  function store()
    {
        $request = new Request();

        $this->service->store($request->product_id, $request->rating);

        return;
    }
}