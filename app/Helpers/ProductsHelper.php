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

    /**
     * @var $user User
     */
    public static function getPresentPlanActive(?User $user = null): ?Plan
    {

        if ($user == null) {
            $user = auth()->user();
        }

        $plans = $user->plans()->where('type', PlansTypeEnum::PRESENT->value)->get();
        $planfree = null;
        $plan = null;
        foreach ($plans as $item) {
            if ($item->duration == PlansDurationEnum::FREE->value) {
                $planfree = $item;
            } else {
                $plan = $item;
                break;
            }

        }

        return $plan ?? $planfree;
    }

    /**
     * @return bool|null
     */
    public static function canAddSpecial(): ?bool
    {
        /**
         * @var $user User
         */
        $user = auth()->user();
        $plan = $user->plans()
            ->where('type', PlansTypeEnum::PRESENT->value)
            ->whereNot('duration', PlansDurationEnum::FREE->value)
            ->where('special_count', '>', 0)
            ->first();
        //info("ADNAN {$plan->name} - {$user->special_product_count} - {$plan->special_count}");
        return $plan != null && $user->special_product_count < $plan->special_count;
    }

    public static function isAvailableCreateProduct(Plan $plan, ?User $user = null)
    {
        if ($user == null) {
            $user = auth()->user();
        }

     $planFree=Plan::where(['type' => PlansTypeEnum::PRESENT->value,'duration' => PlansDurationEnum::FREE->value])->first();
        $productsCountAllow=$planFree?->products_count??20;
        foreach ($user->plans as $plan){
            if ($plan->type != PlansTypeEnum::PRESENT->value){
                if ($plan->duration != PlansDurationEnum::FREE->value &&  $plan->products_count > $productsCountAllow){
                    $productsCountAllow=$plan->products_count;
                }
            }

        }

        $productsCount = Product::where('user_id', $user?->id)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        return $productsCountAllow > $productsCount;
    }
}
