<?php

namespace App\Helpers;

use App\Enums\PlansDurationEnum;
use App\Models\Community;
use App\Models\Plan;
use App\Models\User;

class PlanHelpers
{
    public static function RegisterToPlanFree(User $user)
    {
        try {
            $plan = Plan::where('duration', PlansDurationEnum::FREE->value)->first();
            if ($plan) {
                $user->plans()->syncWithPivotValues([$plan->id], ['subscription_date' => now(), 'expired_date' => now()->addYear()]);
            }
        } catch (\Exception|\Error $e) {
        }
    }

    public static function RegisterToGlobalCommunity(User $user)
    {
        try {
            $groups = Community::where('is_global', true)->pluck('id')->toArray();
            $user->communities()->syncWithoutDetaching($groups);
        } catch (\Exception|\Error $e) {
        }

    }
}
