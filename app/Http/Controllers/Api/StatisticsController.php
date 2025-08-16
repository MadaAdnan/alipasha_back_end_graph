<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Enums\PlansDurationEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Statistics\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function userCount()
    {
        $counts = \DB::table('users')->selectRaw('
    COUNT(*) as total,
    SUM(CASE WHEN email_verified_at IS NOT NULL THEN 1 ELSE 0 END) as verified_count,
    SUM(CASE WHEN email_verified_at IS NULL THEN 1 ELSE 0 END) as unverified_count'
        )->first();
        return $counts;
    }

    public function userPlans()
    {
        $users = User::whereHas('plans', fn($query) => $query->whereNot('duration', PlansDurationEnum::FREE->value))
            ->with(['plans' => function ($query) {
                $query->where('duration', '!=', PlansDurationEnum::FREE->value)->where('expired_date', '>', now())
                    ->select('plans.*', 'plan_user.expired_date');
            }])
            ->get();
        return response()->json(UserResource::collection($users));
    }

    public function ordersCount()
    {
        $counts = \DB::table('orders')->selectRaw('
    SUM(CASE WHEN status = "complete" THEN 1 ELSE 0 END) as completed_count,
    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count,
    SUM(CASE WHEN status = "canceled" THEN 1 ELSE 0 END) as canceled_count,
   '
        )->first();
        return $counts;
    }
}
