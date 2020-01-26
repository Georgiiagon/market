<?php

namespace App\Controllers;

use Core\View;
use App\Models\User;

class AuthController
{
    public function login()
    {
        $user = (new User())->findViaEmail($_POST['email']);
        if (password_verify($_POST['password'], $user->password)) {
            session_destroy();
            session_start();

            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $user->id;
            $_SESSION["user_last_name"] = $user->last_name;
            $_SESSION["user_first_name"] = $user->first_name;

        } else {
            header('Location: /?error=1');
            exit;
        }

        header('Location: /');
    }

    public function logout()
    {
        session_destroy();
        exit;
    }


    public function registration()
    {
        $firstName = trim(htmlspecialchars($_POST['first_name']));
        $lastName = trim(htmlspecialchars($_POST['last_name']));
        $email = trim(htmlspecialchars($_POST['email']));

        if ($_POST['password'] != $_POST['password_confirmation']) {
            header('Location: /?error=1');
            exit;
        }

        if (!$firstName || !$lastName || !$email || !$_POST['password']) {
            header('Location: /?error=1');
            exit;
        }

        $user = (new User())->findViaEmail($email);

        if ($user->id) {
            header('Location: /?error=1');
            exit;
        }

        $user = new User([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ]);

        $user->save();

        header('Location: /?registration=1');
        exit;
    }
}