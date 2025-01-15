<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Advice;
use App\Models\Plan;
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
        if($plans->count() == 0){
            throw new GraphQLExceptionHandler('أنت غير مشترك بخطة إعلانات');
        }
        /**
         * @var $plan Plan
         */
        foreach ($plans as $plan) {
            $expiredDate = Carbon::parse($plan->pivot->expired_at);
            $currentPlan = $plan;
            break;
        }
        if (now()->greaterThan($expiredDate) || $currentPlan?->ads_count >= $myAdvices) {
            throw new GraphQLExceptionHandler('خطتك لا تدعم المزيد من الإعلانات يرجى ترقية الحساب للمزيد');
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
