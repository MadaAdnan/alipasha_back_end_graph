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
        $slider=Slider::where('user_id',auth()->id())->get();
        $viewsCount=$advices->sum('views_count')+$slider->sum('views_count');
        return [
            'advices' => $advices,
            'advice_count' => $advices->count(),
            'my_balance'=>auth()->user()?->getTotalBalance()??0,
            'my_point'=>auth()->user()?->gettotalPoint()??0,
            'my_wins'=>0,
            'views'=>$viewsCount,
            'slider_count'=>$slider->count(),
        ];
    }
}
