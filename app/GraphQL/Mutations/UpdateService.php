<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\Models\Product;

final class UpdateService
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $userId = auth()->id();
        $serviceId = $args['id'];
        $product = Product::service()->where('user_id', $userId)->find($serviceId);
        $product->update([
            'name' => \Str::words(10,$data['info'])??'',
            'city_id' => $data['city_id'] ?? $product->city_id,
            'info' => $data['info'] ?? null,
            'expert'=>\Str::words(10,$data['info'])??'',
            'tags' => $data['tags'] ?? null,
            'category_id' => $data['category_id'] ?? $product->category_id,
            'sub1_id' => $data['sub1_id'] ?? $product->sub1_id,
            'email' => $data['email'] ?? $product->email,
            'phone' => $data['phone'] ?? $product->phone,
            'address' => $data['address'] ?? $product->address,
        ]);

        if (isset($data['attach'])) {
            $product->clearMediaCollection('image');
            $product->addMedia($data['attach'])->toMediaCollection('image');
        }
        return $product;
    }
}
