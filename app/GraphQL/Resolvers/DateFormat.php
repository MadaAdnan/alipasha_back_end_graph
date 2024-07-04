<?php declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;

final class DateFormat
{

   public static function getDateTime($root):array
   {
       return [
           'date'=>$root->created_at->format('Y-m-d'),
           'time'=>$root->created_at->format('h:i a'),
       ];
   }


}
