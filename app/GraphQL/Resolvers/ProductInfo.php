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
             return   "{$root->info} <br/> <b>العنوان :</b> {$root->address}<br/> {$root->phone}";
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

    /**
     * @param $root Product
     * @return boolean
     */
    public static function isDelivery($root): bool
    {
        if(auth()->check() ){
            $userIsDelivery= auth()->user()->city?->is_delivery==true;
        }else{
            $userIsDelivery= true;
        }

        $cityIsDelivery=$root->city->is_delivery==true;
        $productIsDelivery=$root->is_delivery==true;
        return $userIsDelivery && $cityIsDelivery && $productIsDelivery;
    }





}
