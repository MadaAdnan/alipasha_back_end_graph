<?php

namespace App\GraphQL\Resolvers;

use App\Models\Product;
use App\Models\Rate;

class VoteAvg
{
    /**
     * @param $root Product
     * @return float
     */
    public static function getAvg($root): float
    {
        return (float)$root->rates()->avg('vote');

    }


    /**
     * @param $root Product
     */
    public static function isRate($root): bool
    {
        if (auth()->check()) {
            return Rate::where(['product_id' => $root->id, 'user_id' => auth()->id()])->exists();
        }
        return false;
    }
}
