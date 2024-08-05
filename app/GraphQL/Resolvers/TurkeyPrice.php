<?php

namespace App\GraphQL\Resolvers;

final class TurkeyPrice
{

    public static function getTurkeyPrice($root)
    {
        $usd=33;
        return [
            "price"=>$root->price*$usd,
            "discount"=>$root->discount*$usd,
        ];
    }



}
