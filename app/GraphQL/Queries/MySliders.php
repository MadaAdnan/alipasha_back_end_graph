<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Slider;

final class MySliders
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return Slider::where('user_id', auth()->id())->orderBy('expired_date')->get();
    }
}
