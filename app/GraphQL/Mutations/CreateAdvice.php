<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Advice;
use App\Models\Product;

final class CreateAdvice
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {

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
            'status' =>  ProductActiveEnum::PENDING->value,
            'expired_date'=>now()->addWeek(),


        ]);

        if (isset($data['image'])) {
            $advice->addMedia($data['image'])->toMediaCollection('image');
        }
        return $advice;
    }
}
