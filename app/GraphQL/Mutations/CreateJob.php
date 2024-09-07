<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Product;
use Carbon\Carbon;

final class CreateJob
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {

        $data = $args['input'];

        $userId = auth()->id();
        try{
            $product = Product::create([
                'user_id' => $userId,
                'name' =>  \Str::words($data['info'], 10),
                'info' => $data['info'] ?? null,
                'city_id' => $data['city_id'] ?? null,
                'tags' => $data['tags'] ?? null,
                'type' => $data['type'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'start_date' => isset($data['start_date']) ? Carbon::parse($data['start_date']) : null,
                'end_date' => isset($data['end_date']) ? Carbon::parse($data['end_date']) : null,
                'code' => $data['code'] ?? null,
                'url' => $data['url'] ?? null,
                'active' => ProductActiveEnum::PENDING->value,
                'expert' => \Str::words($data['info'], 10),
                'category_id' => $data['category_id'] ?? null,
                'sub1_id' => $data['sub1_id'] ?? null,
                'sub2_id' => $data['sub2_id'] ?? null,
                'sub3_id' => $data['sub3_id'] ?? null,
                'sub4_id' => $data['sub4_id'] ?? null,
            ]);
            if ( $data['attach'] !== null) {
                foreach ($data['attach'] as $value)
                    $product->addMedia($value)->toMediaCollection('docs');
            }

            return $product;
        }catch(\Exception|\Error $exception){
            throw new GraphQLExceptionHandler($exception->getMessage());
        }



    }
}
