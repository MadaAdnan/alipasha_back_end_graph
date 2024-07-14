<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\Models\Slider;

final class UpdateSlider
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $sliderId = $args['id'];
        $data = $args['input'];
        $userId = auth()->id();
        $slider = Slider::where('user_id', $userId)->find($sliderId);
        if (!$slider) {
            throw new \Exception('الإعلان غير موجود');
        }
        /*$slider->update([

          //  'status' => ProductActiveEnum::PENDING->value,
            //'city_id' => $data['city_id'] ?? null,
            'category_id' => $data['category_id'],
            'url' => $data['url'],
        ]);*/
        if (isset($data['image']) && $data['image'] !== null) {
            $slider->clearMediaCollection('image');
            $slider->addMedia($data['image'])->toMediaCollection('image');
        }
    }
}
