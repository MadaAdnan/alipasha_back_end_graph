<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Advice;
use App\Models\Slider;

final class MyAdvice
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $advices = Advice::where('user_id', auth()->id())->latest('expired_date')->get();
        $sliderCount=Slider::where('user_id',auth()->id())->count();
        $viewsCount=$advices->sum('views_count');
        return [
            'advices' => $advices,
            'advice_count' => $advices->count(),
            'my_balance'=>45.5,
            'my_point'=>20,
            'my_wins'=>10.7,
            'views'=>$viewsCount,
            'slider_count'=>$sliderCount,
        ];
    }
}
