<?php

namespace App\GraphQL\Resolvers;

use App\Models\Product;
use App\Models\Rate;

class PivotCommunity
{
    /**
     * @param $root Product
     * @return float
     */
    public static function getPivot($root): null | array
    {
        $community= auth()->user()?->communities()->find($root->id);
            if($community){
                return [
                    'is_manager'=>$community->pivot->is_manager,
                    'notify'=>$community->pivot->notify,
                ];
            }
            return null;

    }



}
