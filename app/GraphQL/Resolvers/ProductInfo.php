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
             return   "<b>العنوان :</b> {$root->address}<br/> {$root->info}";
           }
           return $root->name .' '.$root->info;
       }





}
