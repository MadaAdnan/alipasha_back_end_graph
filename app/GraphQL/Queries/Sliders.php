<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Slider;

final class Sliders
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $category_id = $args['category_id']??null;
        $user_id = $args['user_id']??null;
        $city_id = $args['city_id']??null;
        $type = $args['type']??null;

        $sliders = Slider::
        when($category_id != null, fn($query) => $query->where('category_id', $category_id))
            ->when($user_id != null, fn($query) => $query->where('user_id', $user_id))
            ->when($city_id != null, fn($query) => $query->where('city_id', $city_id))
            ->when($type != null, fn($query) => $query->whereHas('category', fn($query) => $query->where('categories.type', $type)))
            ->with(['category', 'user'])->get();
        return $sliders;
    }
}
