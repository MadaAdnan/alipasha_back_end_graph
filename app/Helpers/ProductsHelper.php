<?php

namespace App\Helpers;

use App\Enums\PlansDurationEnum;
use App\Enums\PlansTypeEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Plan;
use App\Models\Product;
use App\Models\User;

class ProductsHelper
{


    public static function getPresentPlanActive(): ?Plan
    {
        /**
         * @var $user User
         */
        $user = auth()->user();
        $plan = $user->plans()->where('type', PlansTypeEnum::PRESENT->value)->wherePivot('expired_date', '>', now())->first();

        return $plan;
    }

    public static function isAvailableCreateProduct(Plan $plan)
    {

        $productsCount = Product::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->where('user_id', auth()->id())->count();
        if ($productsCount >= $plan->products_count) {
           return false;
        }
        return true;
    }
}
