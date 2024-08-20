<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\ErrorGraphHandler;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Plan;
use App\Models\User;

final class SubscribePlan
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
       $planId=$args['id'];
       $plan=Plan::find($planId);
        /**
         * @var $user User
         */
       $user= auth()->user();
       if(!$plan){
           throw new \Exception('test');
       }

       $balance=$user->getTotalBalance();
       $planPrice=$plan->is_discount?$plan->discount:$plan->price;
       if($balance<$planPrice){
           throw new GraphQLExceptionHandler('لا تملك رصيد كافي');
       }
       $user->plans()->syncWithPivotValues($planId,['expired_date'=>now()->addMonth(),'subscription_date'=>now()],false);

       return $user;
    }
}
