<?php

namespace App\Http\Controllers\Api;

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
        $users = User::
            with(['plans' => function ($query) {
                $query->where('duration','!=',PlansDurationEnum::FREE->value)->where('expired_date', '>', now())
                    ->select('plans.*', 'plan_user.expired_date');
            }])
            ->get();
        return response()->json(UserResource::collection($users));
    }
}
