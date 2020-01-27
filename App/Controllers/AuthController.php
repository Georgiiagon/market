<?php

namespace App\Controllers;

use App\Services\AuthService;
use Core\Request;

class AuthController
{
    protected $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function login()
    {
        $request = new Request();

        $this->service->login($request->email, $request->password);
    }

    public function logout()
    {
        $this->service->logout();
    }

    public function registration()
    {
        $request = new Request();

        $this->service->registration(
            $request->first_name,
            $request->last_name,
            $request->email,
            $request->password,
            $request->password_confirmation
        );
    }
}