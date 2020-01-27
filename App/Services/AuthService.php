<?php


namespace App\Services;

use App\Models\User;

class AuthService
{

    public function login($email, $password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /?error=1');
            exit;
        }

        $user = (new User)->findViaEmail($email);

        if (password_verify($password, $user->password))
        {
            session_destroy();
            session_start();

            $_SESSION["loggedin"] = true;
            $_SESSION["user_id"] = $user->id;
            $_SESSION["user_last_name"] = $user->last_name;
            $_SESSION["user_first_name"] = $user->first_name;
        }
        else
        {
            header('Location: /?error=1');
            exit;
        }

        header('Location: /');
        exit;
    }

    public function logout()
    {
        session_destroy();

        header('Location: /');
        exit;
    }

    public function registration($firstName, $lastName, $email, $password, $passwordConfirmation)
    {
        $firstName = $this->convert($firstName);
        $lastName = $this->convert($lastName);
        $email = $this->convert($email);

        if ($password != $passwordConfirmation)
        {
            header('Location: /?error=1');
            exit;
        }

        if (!$firstName || !$lastName || !$email || !$password || !filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            header('Location: /?error=1');
            exit;
        }

        $user = (new User())->findViaEmail($email);

        if ($user->id)
        {
            header('Location: /?error=1');
            exit;
        }

        $user = new User([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $user->save();

        header('Location: /?registration=1');
        exit;
    }

    public function convert($value)
    {
        return trim(htmlspecialchars($value));
    }
}