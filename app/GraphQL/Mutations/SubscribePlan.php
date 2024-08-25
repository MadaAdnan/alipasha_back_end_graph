<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\ErrorGraphHandler;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Balance;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use GraphQL\Error\Error;

final class SubscribePlan
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $planId = $args['id'];
        $plan = Plan::find($planId);
        /**
         * @var $user User
         */
        $user = auth()->user();
        try {
            if (!$plan) {
                throw new \Exception('test');
            }

            $balance = $user->getTotalBalance();
            $planPrice = $plan->is_discount ? $plan->discount : $plan->price;
            $expiredDate = now()->addMonth();
            $subscription_date = now();
            if ($balance < $planPrice) {
                throw new GraphQLExceptionHandler('لا تملك رصيد كافي');
            }
            $subscribePlan = $user->plans()->where('plans.id', $planId)->first();
            if ($subscribePlan) {
                $oldDate = Carbon::parse($subscribePlan->pivot->expired_date);
                $subscription_date = $subscribePlan->pivot->subscription_date;
                if ($oldDate->greaterThanOrEqualTo(now())) {
                    $expiredDate = $oldDate->addMonth();
                }
            }
            \DB::beginTransaction();
            try {
                $user->plans()->syncWithPivotValues($planId, ['expired_date' => $expiredDate, 'subscription_date' => $subscription_date], false);
                Balance::create([
                    'debit' => $planPrice,
                    'credit' => 0,
                    'user_id' => $user->id,
                ]);
                \DB::commit();
            } catch (\Exception | \Error $e) {
                \DB::rollBack();
                throw new GraphQLExceptionHandler($e->getMessage());
            }

        } catch (\Exception | Error $e) {
            throw new GraphQLExceptionHandler($e->getMessage());
        }
        return $user;
    }
}
