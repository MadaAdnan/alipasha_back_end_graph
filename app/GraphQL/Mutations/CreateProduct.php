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
        $user = auth()->user();
        try {
            $product = \App\Models\Product::create([
                'city_id' => $user->city_id,
                'name' => $data['name'] ?? '',
                'video' => $data['video'] ?? '',
                'info' => $data['info'] ?? '',
                'tags' => $data['tags'] ?? null,
                'is_available' => $data['is_available'] ?? false,
                'price' => $data['price'] ?? 0,
                'discount' => $data['discount'] ?? null,
                'is_discount' => (double)$data['discount'] > 0,
                'category_id' => $data['category_id'] ?? null,
                'sub1_id' => $data['sub1_id'] ?? null,
                'sub2_id' => $data['sub2_id'] ?? null,
                'sub3_id' => $data['sub3_id'] ?? null,
                'sub4_id' => $data['sub4_id'] ?? null,

                'active' => ProductActiveEnum::PENDING->value,
                'user_id' => $user->id,

                'type' => 'product',
                'expert' => \Str::words($data['info'], 10),
                'end_date' => $data['period'] != null ? now()->addDays($data['period']) : null,
                'is_delivery' => $data['is_delivery'] ?? false,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,

            ]);

            /*if (isset($data['image']) && $data['image'] !== null) {
                $product->addMedia($data['image'])->toMediaCollection('image');
            }*/

            if (isset($data['images']) && $data['images'] !== null) {
                foreach ($data['images'] as $key => $image) {
                    if ($key == 0) {
                        $product->addMedia($image)->toMediaCollection('image');
                    } else {
                        $product->addMedia($image)->toMediaCollection('images');
                    }
                }
            }
        } catch (\Exception | \Error $e) {
            throw new \Exception($e->getMessage());
        }

        return $product;
    }
}
