<?php

namespace App\GraphQL\Resolvers;

use App\Models\Community;
use App\Models\Product;
use App\Models\Rate;
use Illuminate\Database\Eloquent\Collection;

class CommunityUserResolve
{
    /**
     * @param $root Community
     * @return Collection
     */
    public static function users($root):Collection
    {
        return $root->users->take(3);

    }



}
