<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\Advice;
use App\Models\Plan;
use App\Models\PlanUser;
use App\Models\Product;
use Carbon\Carbon;

final class CreateAdvice
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {

        $plans = auth()->user()->plans()->where('ads_count', '>', 0)->get();
        $myAdvices = Advice::where(['status' => ProductActiveEnum::ACTIVE->value, 'user_id' => auth()->id()])->count();
        $currentPlan = null;
        $expiredDate = now();
        $user=auth()->user();

        if(!auth()->user()->is_active){
            throw new GraphQLExceptionHandler('تم حظر حسابك يرجى مراجعة الإدارة');
        }
        if($plans->count() == 0){
            throw new GraphQLExceptionHandler('أنت غير مشترك بخطة إعلانات');
        }
        /**
         * @var $plan Plan
         */
        foreach ($plans as $plan) {
            $planUser=PlanUser::where(['plan_id' => $plan->id, 'user_id' => auth()->id()])->where('expired_date','>=',now())->first();
            $expiredDate = $planUser->expired_date;
            $currentPlan = $planUser->plan;
            break;
        }
//      throw new GraphQLExceptionHandler($expiredDate);
        if (now()->greaterThan($expiredDate) || ($currentPlan?->ads_count >= $myAdvices)) {
            $data=[
                'title'=>'تنبيه',
                'body'=>'وصلت لحد النشر المسموح لك شهريا انتظر للشهر القادم او قم بترقية حسابك لتحصل على النشر المفتوح'
            ];
            $job=new SendFirebaseNotificationJob([$user->device_token], $data);
            dispatch($job);
            //
            throw new GraphQLExceptionHandler("خطتك لا تدعم المزيد من الإعلانات يرجى ترقية الحساب للمزيد");
        }
        $data = $args['input'];
        // throw new GraphQLExceptionHandler($data['image']);
        $userId = auth()->id();
        $advice = Advice::create([
            'name' => $data['name'] ?? null,
            'url' => $data['url'] ?? null,
            'city_id' => $data['city_id'] ?? auth()->user()->city_id,
            'category_id' => $data['category_id'] ?? null,
            'sub1_id' => $data['sub1_id'] ?? null,
            'user_id' => $userId,
            'status' => ProductActiveEnum::PENDING->value,
            'expired_date' => $expiredDate,


        ]);

        if (isset($data['image'])) {
            $advice->addMedia($data['image'])->toMediaCollection('image');
        }
        return $advice;
    }
}
