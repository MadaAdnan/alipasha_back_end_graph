<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CategoryTypeEnum;
use App\Enums\PlansTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\GraphQL\Queries\Product;
use App\Models\User;

final class CreateProduct
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $user = auth()->user();
        /**
         * @var $user User
         */
        $user=auth()->user();
        $plan= $user->plans()->where('type',PlansTypeEnum::PRESENT->value)->wherePivot('expired_date','>', now())->first();
        if($plan==null){
            throw new GraphQLExceptionHandler('يرجى الإشتراك بخطة للنشر');
        }
        $productsCount= \App\Models\Product::whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])->where('user_id',auth()->id())->count();
        if($productsCount>=$plan->products_count){
            throw new GraphQLExceptionHandler('لا يمكنك نشر المزيد خلال هذا الشهر يرجى ترقية الخطة لنشر المزيد');
        }
        try {
            $product = \App\Models\Product::create([
                'city_id' => $data['city_id']??$user->city_id,
                'name' => $data['name'] ?? \Str::words($data['info'], 10),
                'video' => $data['video'] ?? '',
                'info' => $data['info'] ?? '',
                'tags' => $data['tags'] ?? null,
                'is_available' => $data['is_available'] ?? false,
                'price' => $data['price'] ?? 0,
                'discount' => $data['discount'] ?? null,
                'is_discount' => isset($data['discount']) && (double)$data['discount'] > 0,
                'category_id' => $data['category_id'] ?? null,
                'sub1_id' => $data['sub1_id'] ?? null,
                'sub2_id' => $data['sub2_id'] ?? null,
                'sub3_id' => $data['sub3_id'] ?? null,
                'sub4_id' => $data['sub4_id'] ?? null,

                'active' => auth()->user()->is_default_active===true?ProductActiveEnum::ACTIVE->value:ProductActiveEnum::PENDING->value,
                'user_id' => $user->id,

                'type' => CategoryTypeEnum::PRODUCT->value,
                'expert' => \Str::words($data['info'], 10),
                'end_date' => $data['period'] != null ? now()->addDays($data['period']) : null,
                'is_delivery' => $data['is_delivery'] ?? false,
                // 'latitude' => $data['latitude'] ?? null,
                //'longitude' => $data['longitude'] ?? null,

            ]);

            /*if (isset($data['image']) && $data['image'] !== null) {
                $product->addMedia($data['image'])->toMediaCollection('image');
            }*/

            $product->colors()->sync($data['colors']??[]);

            $product->attributes()->sync($data['options']??[]);


            if (isset($data['images']) && $data['images'] !== null) {
                foreach ($data['images'] as $key => $image) {
                        $product->addMedia($image)->toMediaCollection('images');
                }
            }
        } catch (\Exception | \Error $e) {
            throw new \Exception($e->getMessage());
        }

        return $product;
    }
}
