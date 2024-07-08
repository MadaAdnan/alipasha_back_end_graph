<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\GraphQL\Queries\Product;

final class CreateProduct
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        info($data['main']);
        $user = auth()->user();
        return \App\Models\Product::create([
            'name' => $data['name'] ?? '',
            'info' => $data['info'] ?? '',
            'tags' => $data['tags'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'sub1_id' => $data['sub1_id'] ?? null,
            'sub2_id' => $data['sub2_id'] ?? null,
            'sub3_id' => $data['sub3_id'] ?? null,
            'sub4_id' => $data['sub4_id'] ?? null,
            'is_discount' => $data['is_discount'] ?? false,
            'discount' => $data['discount'] ?? '',
            'is_available' => $data['is_available'] ?? false,
            'price' => $data['price'] ?? 0,
            'active' => ProductActiveEnum::PENDING->value,
            'user_id' => $user->id,
            'city_id' => $data['city_id'] ?? null,
            'video' => $data['video'] ?? '',
            'type' => 'product',
            'expert' => \Str::words($data['info'], 10),
            'end_date' => $data['end_date'] ?? null,
            'is_delivery' => $data['is_delivery'] ?? false,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,

        ]);
    }
}
