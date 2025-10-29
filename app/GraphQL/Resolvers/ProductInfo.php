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
             return   "{$root->info}";
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

        $cityIsDelivery=$root->city?->is_delivery==true;
        $productIsDelivery=$root->is_delivery==true;
        return $userIsDelivery && $cityIsDelivery && $productIsDelivery /*&& $root->user?->area_id!=null*/;
    }

    /**
     * @param $root Product
     * @return string
     */
    public static function fullPhone($root):string{
        $phone=  $root->phone;
        if(\Str::startsWith($phone, '+')){
            $phone=  \Str::substr($phone, 0,1);
        }elseif(\Str::startsWith($phone, '00')){
            $phone=  \Str::substr($phone, 0,2);
        }elseif (\Str::startsWith($phone, '09')){
            $phone=  \Str::substr($phone, 0,1);
            $phone="{$root->user->phone_code}{$phone}";
        }elseif($phone==''){
            $phone="{$root->user->phone_code}{$root->user->phone}";
        }
        return $phone;
    }





}
