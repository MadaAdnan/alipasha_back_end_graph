<?php

namespace App\GraphQL\Resolvers;

use App\Models\Setting;

final class TurkeyPrice
{

    public static function getTurkeyPrice($root)
    {
        $usd=Setting::first()->dollar_value??35;
        return [
            "price"=>$root->price*$usd,
            "discount"=>$root->discount*$usd,
        ];
    }
    public static function getSyrPrice($root)
    {
        $usd=Setting::first()->dollar_value??14750;
        return [
            "price"=>$root->price*$usd,
            "discount"=>$root->discount*$usd,
        ];
    }



}
