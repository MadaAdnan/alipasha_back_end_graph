<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;

final class UpdateProduct
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $productId = $args['id'];
        $userId = auth()->id();
        try {
            $product = \App\Models\Product::product()->where('user_id', $userId)->find($productId);
            if (!$product) {
                throw new \Exception('المنتج رقم ' . $productId . ' غير موجود');
            }
            $product->update([
                'name' =>\Str::words($data['info'], 10),
                'info' =>$data['info'] ??null,
                'tags' => $data['tags'] ?? null,
                'category_id' => $data['category_id'] ?? null,
                'sub1_id' => $data['sub1_id'] ?? null,
                'sub2_id' => $data['sub2_id'] ?? null,
                'sub3_id' => $data['sub3_id'] ?? null,
                'sub4_id' => $data['sub4_id'] ?? null,
                'is_discount' =>isset($data['discount']) &&   $data['discount']>0,
                'discount' => $data['discount'] ?? null,
                'is_available' => $data['is_available'] ?? false,
                'price' => $data['price'] ?? 0,
                'city_id' => $data['city_id']  ??$product->city_id,
                'video' => $data['video'] ?? '',
                'expert' => \Str::words($data['info'], 10),
                'end_date' => $data['end_date'] ?? null,
                'is_delivery' => $data['is_delivery'] ?? false,
               // 'latitude' => $data['latitude'] ?? null,
               // 'longitude' => $data['longitude'] ?? null,

            ]);
            $product->colors()->sync($data['colors']??[]);
            if (isset($data['images']) && $data['images'] !== null) {
                foreach ($data['images'] as $key=>$image) {
                        $product->addMedia($image)->toMediaCollection('images');
                }
            }
        } catch (\Exception | \Error $e) {
            \Log::info(get_class($e));
            \Log::info($e->getMessage());
            throw new \Exception($e->getMessage());
        }

        return $product;
    }
}
