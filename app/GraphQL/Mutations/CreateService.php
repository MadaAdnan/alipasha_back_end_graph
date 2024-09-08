<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Product;

final class CreateService
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $userId = auth()->id();
        $product = Product::create([
            'user_id' => $userId,
            'type'=>CategoryTypeEnum::SERVICE->value,
            'active' => ProductActiveEnum::PENDING->value,
            'name' => $data['name'] ?? \Str::words($data['info'] ,10),
            'city_id' => $data['city_id'] ?? null,
            'info' => $data['info'] ?? null,
            'expert'=>\Str::words($data['info'] ,10),
            'tags' => $data['tags'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'sub1_id' => $data['sub1_id'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        if (isset($data['image'])) {
            $product->addMedia($data['image'])->toMediaCollection('image');
        }
        return $product;
    }
}
