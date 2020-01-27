<?php

namespace App\Controllers;

use App\Models\Rating;

class RatingController
{

    public  function store()
    {
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
        {
            $rating = (new Rating)->findWhere([
                ['user_id', $_SESSION["user_id"]],
                ['product_id', $_POST['product_id']],
            ]);
        }

        if ($rating->id || isset($_SESSION['rating'][$_POST['product_id']]))
        {
            echo json_encode(['status' => 'error', 'message' => 'You have already rated the product!']);

            return;
        }
        else
        {
            $rating = new Rating([
                'rating' => $_POST['rating'],
                'product_id' => $_POST['product_id'],
                'user_id' => $_SESSION["user_id"] ?? 0,
            ]);
            $rating->save();

            $_SESSION['rating'][$_POST['product_id']] = true;
        }

        echo json_encode(['status' => 'success', 'message' => 'Rating stored']);

        return;
    }
}