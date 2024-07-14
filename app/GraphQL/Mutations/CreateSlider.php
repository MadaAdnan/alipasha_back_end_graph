<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\Models\Slider;

final class CreateSlider
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $userId = auth()->id();
        $slider = Slider::create([
            'user_id' => $userId,
            'status' => ProductActiveEnum::PENDING->value,
            'city_id' => $data['city_id'] ?? null,
            'category_id' => $data['category_id'],
            'url' => $data['url'],
        ]);
        if (isset($data['image']) && $data['image'] !== null) {
            $slider->addMedia($data['image'])->toMediaCollection('image');
        }
    }
}
