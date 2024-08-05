<?php

namespace App\GraphQL\Resolvers;

final class TurkeyPrice
{

    public function getTurkeyPrice($root):array
    {
        $usd=33;
        return [
            "price"=>$root->price*$usd,
            "discount"=>$root->discount*$usd,
        ];
    }



}
