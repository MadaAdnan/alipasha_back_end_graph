<?php

namespace App\GraphQL\Resolvers;

use App\Models\Community;
use App\Models\Product;
use App\Models\Rate;

class CommunityUserResolve
{
    /**
     * @param $root Community
     * @return float
     */
    public static function users($root): float
    {
        return $root->users->take(3);

    }



}
