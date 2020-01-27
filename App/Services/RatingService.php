<?php


namespace App\Services;

use App\Models\Rating;
use Core\Auth;

class RatingService
{
    public function store($product_id, $rating_score)
    {
        if ((new Auth)->loggedin())
        {
            $rating = (new Rating)->findWhere([
                ['user_id', (new Auth)->id],
                ['product_id', $product_id],
            ]);
        }

        if ($rating->id || isset($_SESSION['rating'][$product_id]))
        {
            echo json_encode(['status' => 'error', 'message' => 'You have already rated the product!']);

            return;
        }
        else
        {
            $rating = new Rating([
                'rating' => $rating_score,
                'product_id' => $product_id,
                'user_id' => (new Auth)->id ?? 0,
            ]);

            $rating->save();

            $_SESSION['rating'][$product_id] = true;
        }

        echo json_encode(['status' => 'success', 'message' => 'Rating stored']);

        return;
    }
}