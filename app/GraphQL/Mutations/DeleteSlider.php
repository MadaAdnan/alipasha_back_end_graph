<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Slider;

final class DeleteSlider
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $sliderId = $args['id'];
        $userId = auth()->id();

        $slider = Slider::where('user_id', $userId)->find($sliderId);
        if (!$slider) {
            throw new \Exception('الإعلان غيرموجود');
        }
        $slider->delete();
        return $slider;
    }
}
