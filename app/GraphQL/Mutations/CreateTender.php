<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CategoryTypeEnum;
use App\Enums\PlansTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Helpers\ProductsHelper;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

final class CreateTender
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $userId = auth()->id();
        $plan = ProductsHelper::getPresentPlanActive();
        if ($plan == null) {
            throw new GraphQLExceptionHandler('يرجى الإشتراك بخطة للنشر');
        }
        $isAvailableCreate=ProductsHelper::isAvailableCreateProduct($plan);
        if(!$isAvailableCreate){
            throw new GraphQLExceptionHandler('لا يمكنك نشر المزيد خلال هذا الشهر يرجى ترقية الخطة لنشر المزيد');
        }
        $product = Product::create([
            'user_id' => $userId,
            'name' => $data['name'] ?? \Str::words($data['info'], 10),
            'info' => $data['info'] ?? null,
            'city_id' => $data['city_id'] ?? auth()->user()->city_id,
            'tags' => $data['tags'] ?? null,
            'type' => CategoryTypeEnum::TENDER->value,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'start_date' => isset($data['start_date']) ? Carbon::parse($data['start_date']) : null,
            'end_date' => isset($data['end_date']) ? Carbon::parse($data['end_date']) : null,
            'code' => $data['code'] ?? null,
            'url' => $data['url'] ?? null,
            'active' => auth()->user()->is_default_active === true ? ProductActiveEnum::ACTIVE->value : ProductActiveEnum::PENDING->value,

            'expert' => \Str::words($data['info'], 10),
            'category_id' => $data['category_id'] ?? null,
            'sub1_id' => $data['sub1_id'] ?? null,
            'sub2_id' => $data['sub2_id'] ?? null,
            'sub3_id' => $data['sub3_id'] ?? null,
            'sub4_id' => $data['sub4_id'] ?? null,
        ]);
        if (isset($data['attach'])) {
            if (is_array($data['attach'])) {
                foreach ($data['attach'] as $doc) {
                    $product->addMedia($doc)->toMediaCollection('docs');
                }
            } else {
                $product->addMedia($data['attach'])->toMediaCollection('docs');
            }
        }
        return $product;
    }
}
