<?php

namespace App\Helpers;

use App\Enums\PlansDurationEnum;
use App\Models\Balance;
use App\Models\Community;
use App\Models\Plan;
use App\Models\User;

class GlobalHelper
{
    public static function RegisterToPlanFree(User $user)
    {
        try {
            $plan = Plan::where('duration', PlansDurationEnum::FREE->value)->first();
            if ($plan) {
                $user->plans()->syncWithPivotValues([$plan->id], ['subscription_date' => now(), 'expired_date' => now()->addYear()]);
            }
        } catch (\Exception|\Error $e) {
            \Log::error("ERROR PLAN REGISTAER: {$e->getMessage()}");
        }
    }

    public static function RegisterToGlobalCommunity(User $user)
    {
        try {
            $groups = Community::where('is_global', true)->pluck('id')->toArray();
            $user->communities()->syncWithoutDetaching($groups);
        } catch (\Exception|\Error $e) {
            \Log::error("ERROR Community REGISTAER: {$e->getMessage()}");
        }

    }

    public static function addRegisterWinToUser(User $user,$amount){
        Balance::create([
            'user_id' => $user->id,
            'info' => 'كافئة فتح حساب جديد غير قابل للسحب ومخصص لشراء الإعلانات او الشحن فقط',
            'debit' => 0,
            'credit' => $amount,
        ]);
    }

    public static function formatNumber($number){
        if($number<1000){
            return $number;
        }elseif ($number>=1000 && $number<1000000) {
            return number_format($number / 1000, 1) . 'K';
        }elseif ($number>=1000000 && $number<1000000000) {
            return number_format($number / 1000000, 1) . 'M';
        }elseif ($number>=1000000000 && $number<1000000000000) {
            return number_format($number / 1000000000, 1) . 'B';
        }elseif ($number>=1000000000000) {
            return number_format($number / 1000000000000, 1) . 'T';
        }

    }
}
