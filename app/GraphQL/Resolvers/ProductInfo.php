<?php

namespace App\GraphQL\Resolvers;

use App\Enums\CategoryTypeEnum;
use App\Models\Like;
use App\Models\Product;
use App\Models\Rate;

class ProductInfo
{
    /**
     * @param $root Product
     * @return float
     */
    public static function getAddressWithInfo($root): string
    {

           if($root->type==CategoryTypeEnum::SERVICE->value){
             return   "{$root->info} <br/> <b>العنوان :</b> {$root->address}<br/>";
           }
           return $root->name .' '.$root->info;
       }

    public static function getNameWithExpert($root): string
    {

        if($root->type!=CategoryTypeEnum::PRODUCT->value && $root->type!=CategoryTypeEnum::RESTAURANT->value){
            return   "{$root->name} {$root->expert}";
        }
        return $root->expert;
    }





}
